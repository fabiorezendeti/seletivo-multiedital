<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process\ExamRoomBooking;
use App\Models\Process\Notice;
use App\Models\Address\City;
use App\Models\Organization\Campus;
use App\Models\Process\Subscription;
use App\Policies\ExamRoomBookingPolicy;
use App\Policies\NoticePolicy;
use App\Repository\CampusRepository;
use App\Models\Process\ExamLocation;
use App\Models\Process\ExamRoom;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Services\CsvLib\CsvFileService;
use Illuminate\Support\Facades\DB;

class AllocationOfExamRoomBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Notice $notice)
    {
        $campuses = (new CampusRepository())->getCampusesByNotice($notice);
        $examLocations = ExamLocation::whereIn('campus_id',$campuses->pluck('id'))
                                        ->isActivated()
                                        ->orderBy('local_name')
                                        ->paginate();
        return view('admin.notices.allocation-of-exam-room.index', compact('notice', 'examLocations'));
    }

    public function importExamRooms(Notice $notice){

        try{
            DB::transaction(function () use ($notice) {
                $examRooms = ExamRoom::select('exam_location_id','name','capacity','for_special_needs','priority','active')->get();
                $examRoomBookings = $examRooms->map(function($examRoom) use ($notice) {
                    $data = $examRoom->toArray();
                    $data['notice_id'] = $notice->id;
                    return $data;
                });
                ExamRoomBooking::insert($examRoomBookings->toArray());
            });
        }catch (Exception $e){
            return view('admin.notices.allocation-of-exam-room.index', compact('notice'))->with('error','Um erro impediu a importação dos locais de prova.');
        }
        return redirect()->route('admin.notices.allocation-of-exam-room.index', ['notice' => $notice])->with('success', 'Salas importadas com sucesso!');
    }

    public function indexManual(Notice $notice, Request $request){

        $examDate = Carbon::createFromFormat('Y-m-d H:i:s', $notice->exam_date);
        if ($examDate->isPast()) {
            return redirect()->route('admin.notices.allocation-of-exam-room.index', ['notice' => $notice])
                ->with('error', 'Essa funcionalidade não pode ser executada após a data da prova.');
        }
        $campuses = (new CampusRepository())->getCampusesByNotice($notice);
        $examLocations = ExamLocation::whereIn('campus_id',$campuses->pluck('id'))->isActivated();
        $examLocations = $examLocations->whereHas('examRoomBookings',function($q){
            $q->isForSpecialNeeds();
        })->get();

        $subscriptions = $notice->subscriptions()
            ->join('users', 'users.id', '=', 'subscriptions.user_id');

        if ($request->campus) {
            $campus = Campus::find($request->campus);
            $subscriptions = $notice->subscriptions()
                                ->join('users', 'users.id', '=', 'subscriptions.user_id')
                                ->byCampus($campus);
        }

        $examRoomBookings = ExamRoomBooking::with(['examLocation' => function ($q) use ($campuses, $request){
                $q->whereIn('campus_id',$campuses->pluck('id'))
                  ->isActivated();
            }])
            ->where('notice_id','=', $notice->id)
            ->isForSpecialNeeds();
        if ($request->has('campus') && !empty($request->campus)) {
            $examRoomBookings->whereHas('examLocation', function($q) use ($request) {
                $q->where('campus_id', $request->campus);
            });
        }
        $examRoomBookings = $examRoomBookings->get();


        // Issue 397 - filtros adicionais para a alocação manual de candidatos
        $subscriptions = $subscriptions->where('is_homologated', true)
            ->where(function ($query) {
                $query->WhereRaw("cast(additional_test_time_analysis->>'approved' as boolean) is true")
                    ->orWhereRaw("cast(exam_resource_analysis->>'approved' as boolean) is true");
            });


        $subscriptions = $subscriptions->orderBy('users.name')
                                        ->paginate(10, ['subscriptions.*', 'users.name']);

        $campus_selected_id = isset($request->campus) ? $request->campus : null;
        return view('admin.notices.allocation-of-exam-room.manual.index', compact(
            'notice',
            'campuses',
            'examRoomBookings',
            'subscriptions',
            'campus_selected_id'
        ));
    }

    /**
     * Ensalamento automático de candidatos [GET]
     *
     * OBJETIVO: Realizar o ensalamento automático para os inscritos de todos os locais ou de um local específico ($request->exam_location)
     *
     * CONDIÇÕES:
     * - A quantidade de inscrições a serem ensaladas devem obedecer a capacidade da sala ($examRoomBooking->capacity)
     * - As inscrições devem estar ordenadas pelo nome dos inscritos
     * - As inscrições devem ter sido homologadas
     * - As inscrições NÃO podem ser paa portadores de necessidades especiais (ESSAS SÃO ENSALADAS MANUALMENTE)
     * - Somente inscrições que ainda não foram ensaladas
     *
     * @param  \Notice  $notice
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response  ***
     */
    public function autoAllocate(Notice $notice, Request $request)
    {
        $examDate = Carbon::createFromFormat('Y-m-d H:i:s', $notice->exam_date);
        if ($examDate->isPast()) {
            return redirect()->route('admin.notices.allocation-of-exam-room.index', ['notice' => $notice])
                ->with('error', 'Essa funcionalidade não pode ser executada após a data da prova.');
        }
        set_time_limit(0);
        DB::beginTransaction();
        try {
            $campuses = (new CampusRepository())->getCampusesByNotice($notice);
            $examLocations = ($request->exam_location) ? ExamLocation::where('id', $request->exam_location)->isActivated()->get()
                : ExamLocation::whereIn('campus_id', $campuses->pluck('id'))->isActivated()->orderBy('priority')->get();

            foreach ($examLocations as $examLocation) {
                $campus = $examLocation->campus()->first();
                $examRoomBookings = $examLocation->examRoomBookings()
                    ->where('notice_id','=', $notice->id)
                    ->where('for_special_needs', '0')
                    ->isActivated()
                    ->orderBy('priority')
                    ->get();
                foreach ($examRoomBookings as $examRoomBooking) {
                    $subscriptions = $notice->subscriptions()
                        ->join('users', 'users.id', '=', 'subscriptions.user_id')
                        ->byCampus($campus)
                        ->ishomologated()
                        ->where(function ($query) {
                            $query->WhereNull('additional_test_time_analysis')
                                ->orWhereRaw("cast(additional_test_time_analysis->>'approved' as boolean) is not true");
                        })
                        ->where(function ($query) {
                            $query->WhereNull('exam_resource_analysis')
                                ->orWhereRaw("cast(exam_resource_analysis->>'approved' as boolean) is not true");
                        })
                        ->whereNull('exam_room_booking_id')
                        ->orderBy('users.name')
                        ->limit($examRoomBooking->capacity)
                        ->get(['subscriptions.*', 'users.name']);
                    foreach ($subscriptions as $subscription) {
                        $subscription->exam_room_booking_id = $examRoomBooking->id;
                        $subscription->save();
                    }
                }
            }
            DB::commit();
            return redirect()->route('admin.notices.allocation-of-exam-room.index', ['notice' => $notice])
                ->with('success', 'Ensalamento automático executado com sucesso');
        } catch (Exception $exception) {
            DB::rollback();
            return exception;
            return redirect()->route('admin.notices.allocation-of-exam-room.index', ['notice' => $notice])
                ->with('error', 'Ocorreram erros no ensalamento automático.');
        }
    }

    /**
     * Desfaz ensalamento automático de candidatos [GET/DELETE]
     *
     * OBJETIVO: Desensalar os candidatos do edital selecionado.
     *
     * RESTRIÇÕES: Apenas inscrições homologadas e que não sejam portadoras de necessidades especiais
     *
     * OBS.: Se a variável $request->exam_location é fornecida o desensalamento deve ser aplicado apenas as inscrições
     * referente ao local especificado na variável
     *
     * @param  \Notice  $notice
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response  ***
     */
    public function undoAutoAllocate(Notice $notice, Request $request)
    {
        $examDate = Carbon::createFromFormat('Y-m-d H:i:s', $notice->exam_date);
        if ($examDate->isPast()) {
            return redirect()->route('admin.notices.allocation-of-exam-room.index', ['notice' => $notice])
                ->with('error', 'Essa funcionalidade não pode ser executada após a data da prova.');
        }
        set_time_limit(0);
        DB::beginTransaction();
        try {
            if (!isset($request->exam_location)) {
                $notice->subscriptions()
                    ->isHomologated()
                    ->update(['exam_room_booking_id' => null]);
            } else {
//                dd("por campus");
                $result = Subscription::whereHas('examRoomBooking', function ($query) use ($request) {
                    $query->where('exam_location_id', $request->exam_location);
                })
                    ->isHomologated()
                    ->update(['exam_room_booking_id' => null]);
            }
            DB::commit();
            return redirect()->route('admin.notices.allocation-of-exam-room.index', ['notice' => $notice])
                ->with('success', 'Desfazimento do ensalamento automático executado com sucesso');
        } catch (Exception $exception) {
            DB::rollback();
            return redirect()->route('admin.notices.allocation-of-exam-room.index', ['notice' => $notice])
                ->with('error', 'Ocorreram erros ao desfazer o ensalamento automático.');
        }
    }

    /**
     * Relatório de Ensalamento
     *
     * @param  Notice $notice
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Notice $notice, Request $request)
    {
        $campuses = (new CampusRepository())->getCampusesByNotice($notice);

        if (!$request->type) {
            return view('admin.reports.allocation-of-exam-room', compact('notice', 'campuses'));
        }

        if(is_null($request->campus))
            return back();

        $campusName = Campus::find($request->campus)->name;
        $campusName = str_replace(' ', '', $campusName);

        $examLocations = ExamLocation::where('campus_id',$request->campus);
        $examLocations = $examLocations
                            ->isActivated()
                            ->with(['examRoomBookings' => function ($q) use ($notice){
                                $q->orderBy('priority');
                                $q->orderBy('name');
                                $q->where('notice_id',$notice->id);
                                $q->with(['subscriptions' => function ($q) {
                                    $q->join('users', 'users.id', '=', 'subscriptions.user_id')
                                    ->orderBy('users.name');
                                }]);
                            }])
                            ->get();
        switch ($request->type) {
            case 'csv':
                $csvFileService = new CsvFileService();
                $csvFileService->insertOne(
                    ['Inscrição','Nome','CPF','Curso','Campus','Sala']
                );
                foreach ($examLocations as $examLocation)
                {
                    foreach ($examLocation->examRoomBookings as $examRoomBooking)
                    {
                        foreach ($examRoomBooking->subscriptions as $subs)
                        {
                            $csvFileService->insertOne([
                                $subs->subscription_number,
                                $subs->user->name,
                                $subs->user->cpf,
                                $subs->distributionOfVacancy->offer->courseCampusOffer->course->name,
                                $examLocation->campus->name,
                                $examRoomBooking->name
                            ]);
                        }
                    }
                }
                return $csvFileService->output("ensalamento".$campusName.".csv");
                break;
            case 'lista':
                return view('admin.notices.allocation-of-exam-room.report-list',compact('notice', 'examLocations'));
                break;
            default:
                # html
                return view('admin.notices.allocation-of-exam-room.report',compact('notice', 'examLocations'));
                break;
        }
    }


}
