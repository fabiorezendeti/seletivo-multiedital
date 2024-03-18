<?php

namespace App\Http\Controllers\Admin;

use App\Models\Process\ExamRoomBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Audit\Justify;
use App\Models\Process\Notice;
use App\Models\Security\Audit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Process\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Models\Process\SelectionCriteria;
use App\Models\Process\ExamLocation;
use App\Models\Process\ExamRoom;
use App\Services\CsvLib\CsvFileService;
use Exception;
use SebastianBergmann\Environment\Console;
use App\Models\Process\Offer;
use App\Models\Process\DistributionOfVacancies;

class NoticeSubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Notice $notice, Request $request)
    {
        //exibe filtro com as ofertas apenas dos campi que a CRA tem permissão.
        if (Gate::check('isAcademicRegister')) {
            $campuses = Auth::user()->permissions()
                ->select('permissions.campus_id')
                ->where('role_id', 2)
                ->where('user_id', Auth::user()->id)->get();
            $offers = $notice->offers()
                ->whereHas('courseCampusOffer', function ($q) use ($campuses) {
                    $q->whereIn('campus_id', $campuses->pluck('campus_id'));
                })->orderBy('course_campus_offer_id', 'asc')->get();
        }else{
            $offers = $notice->offers()->orderBy('course_campus_offer_id', 'asc')->get();
        }

        $subscriptions = $notice->subscriptions();
        if($request->offer){
            $offer = Offer::findOrFail($request->offer);
            $subscriptions->whereHas('distributionOfVacancy', function ($q) use ($offer) {
                $q->where('offer_id', $offer->id);
            });
        }else{
            $subscriptions->with(['distributionOfVacancy' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->with(['courseCampusOffer' => function ($q) {
                        $q->with(['course', 'campus']);
                    }]);
                }]);
            }]);
        }

        if (Gate::denies('isAdmin')) {
            $campuses = Auth::user()->permissions()
                ->select('permissions.campus_id')
                ->where('role_id', 2)
                ->where('user_id', Auth::user()->id)->get();
                $subscriptions->byCampusesByIds($campuses->pluck('campus_id'));
        }
        if ($request->search) {
            $subscriptions->whereHas('user', function ($q) use ($request) {
                $q->where('cpf', $request->search);
            });
            $subscriptions->orWhere(['subscription_number' => (int)$request->search])->where('notice_id', $notice->id);
        }

        $subscriptions = $subscriptions->orderBy('updated_at')->paginate();
        return view('admin.notices.subscriptions.index', compact(
            'notice',
            'subscriptions',
            'offers'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Notice $notice, $id)
    {
        return Subscription::with([
            'user',
            'distributionOfVacancy.offer.courseCampusOffer.course',
            'distributionOfVacancy.offer.courseCampusOffer.campus'
        ])->findOrFail($id)->toJson();
    }

    public function homologate(Notice $notice, Subscription $subscription)
    {
        $subscription->is_homologated = true;
        $save = $subscription->save();
        if ($save) return response()->json($subscription, 200);
        return response()->json(['error' => true], 204);
    }

    public function homologateInBatch(Notice $notice)
    {
        try {
            $notice->subscriptions()
                ->update(['is_homologated' => true]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage(), ['Homologate In Batch']);
            return back()->with('error', 'Um erro ocorreu ao realizar a homologação em lote.');
        }
        return redirect()->route('admin.notices.show', ['notice' => $notice])
        ->with('success', 'A homologação em lote ocorreu com sucesso!');
    }

    public function revokeHomologateInBatch(Notice $notice){
        try {
            $notice->subscriptions()
                ->update(['is_homologated' => null]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage(), ['Revoke Homologate In Batch']);
            return back()->with('error', 'Um erro ocorreu ao desfazer a homologação em lote.');
        }
       return redirect()->route('admin.notices.show', ['notice' => $notice])
                ->with('success', 'A revogação da homologação em lote ocorreu com sucesso!');
    }


    public function eliminate(Request $request, Notice $notice, Subscription $subscription)
    {
        $subscription->setElimination(Carbon::now(), $request->reason, Auth::user());
        $save = $subscription->save();
        try {
            $table = $notice->getEnrollmentCallTableNameByCriteria($subscription->distributionOfVacancy->selectionCriteria);
            DB::table($table)
                ->where('subscription_id',$subscription->id)
                ->update(['status' => 'não matriculado']);
        } catch (QueryException $exception) {
            Log::warning($exception->getMessage(),['ELIMINATION']);
        }
        if ($save) return response()->json($subscription, 200);
        return response()->json(['error' => true], 204);
    }

    public function updateMean(Request $request, Notice $notice, Subscription $subscription)
    {
        if (!Gate::allows('allowUpdateScores',$notice)) return response()->json(['error' => 'Não pode ter a média alterada'], 403);
        DB::beginTransaction();
        try {
            $table = $notice->getScoreTableNameForCriteriaId($subscription->distributionOfVacancy->selection_criteria_id);
            DB::table($table)
                ->where('subscription_id',$subscription->id)
                ->update(['media' => $request->media, 'media_verificada' => true]); // grava a média e marca como verificada
            Justify::create([ // Auditoria
                    'justify' => "Correção da média após validar documentos",
                    'data' => json_encode($request),
                    'author_id' => Auth::id(),
                    'uri' => $request->getUri()
            ]);
            DB::commit();
            return $this->show($notice, $subscription->id);
        } catch (QueryException $exception) {
            DB::rollBack();
            Log::warning($exception->getMessage(),['UPDATE_MEAN']);
            return response()->json(['error' => true], 500);
        }
    }

    public function trackingInfo(Notice $notice, Subscription $subscription)
    {
        $data = [];
        foreach ($subscription->freezes()->orderBy('created_at')->get() as $freeze) {
            $auditStartDate = $freeze->created_at->addMinutes(-1);
            $auditEndDate = $freeze->created_at->addMinutes(+1);
            $navigation = Audit::where('user_id', $subscription->user->id)
                ->select('created_at', 'uri', 'content', 'method')
                ->where('method', 'POST')
                ->where('uri', 'like', "%notice/{$notice->id}/offer/%/subscription%")
                ->whereBetween('created_at', [$auditStartDate, $auditEndDate])
                ->get();
            $data["$freeze->created_at"]['freeze'] = $freeze->content;
            $data["$freeze->created_at"]['navigation'] = $navigation;
        }
        return view('admin.notices.subscriptions.tracking.show', compact(
            'notice',
            'subscription',
            'data'
        ));
    }

    /**
     * Cancela uma homologação
     *
     * @param  Notice  $notice
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function cancel(Notice $notice, Subscription $subscription)
    {
        $subscription->is_homologated = null;
        $save = $subscription->save();
        if ($save) return response()->json($subscription, 200);
        return response()->json(['error' => true], 204);
    }

    public function indexPPI(Notice $notice, Request $request)
    {
        $subscriptions = $notice->getConvokePPI();

        if ($request->search) {
            $subscriptions->whereHas('user', function ($q) use ($request) {
                $q->where('cpf', $request->search);
            });
            $subscriptions->orWhere('subscription_number', (int)$request->search);
        }
        $subscriptions = $subscriptions->paginate();
        return view('admin.notices.subscriptions.ppi', compact(
            'notice',
            'subscriptions'
        ));
    }

    public function downloadPPIWithContact(Notice $notice, Request $request, CsvFileService $csvFileService)
    {
        $subscriptions = $notice->getConvokePPI();
        $subscriptions->with('user.contact');
        $subscriptions = $subscriptions->get();
        $csvFileService->insertOne(
            ['Inscrição','Nome','Campus/Curso','Ação Afirmativa','Fone 1','Fone 2','E-mail']
        );
        foreach ($subscriptions as $subs)
        {
            $csvFileService->insertOne([
                $subs->subscription_number,
                $subs->user->name,
                $subs->distributionOfVacancy->offer->getString(),
                $subs->distributionOfVacancy->affirmativeAction->slug,
                $subs->user->contact->phone_number ?? 'Não informado',
                $subs->user->contact->alternative_phone_number ?? 'Não informado',
                $subs->user->email
            ]);
        }
        return $csvFileService->output("Lista de candidatos ppi com contato.csv");


    }

    public function checkPPI(Notice $notice, Subscription $subscription)
    {
        $subscription->is_ppi_checked = true;
        $save = $subscription->save();
        if ($save) return response()->json($subscription, 200);
        return response()->json(['error' => true], 204);
    }

    public function uncheckPPI(Notice $notice, Subscription $subscription)
    {
        $subscription->is_ppi_checked = false;
        $save = $subscription->save();
        if ($save) return response()->json($subscription, 200);
        return response()->json(['error' => true], 204);
    }

    public function updateExamRoomBooking(Notice $notice, Subscription $subscription, Request $request)
    {
        $examDate = Carbon::createFromFormat('Y-m-d H:i:s', $notice->exam_date);
        if ($examDate->isPast()) {
            return response()->json(['error' => true, 'message' => 'Essa funcionalidade não pode ser executada após a data da prova.'], 400);
        }

        $examRoomBooking = ExamRoomBooking::find($request->exam_room_booking_id);

        if($examRoomBooking->notice_id != $notice->id){
            return response()->json(['error' => true, 'message' => 'A sala de exame selecionada não pertence ao edital atual.'], 400);
        }

        if($subscription->distributionOfVacancy->offer->courseCampusOffer->campus_id != $examRoomBooking->examLocation->campus_id){
            return response()->json(['error' => true, 'message' => 'A sala de exame selecionada não pertence ao campus do candidato'], 400);
        }

        if ($examRoomBooking && $examRoomBooking->for_special_needs == '1'){
            $subscription->exam_room_booking_id = $request->exam_room_booking_id;
            if ($subscription->save())
                return $this->show($notice, $subscription->id);
        }
        return response()->json(['error' => true], 400);
    }

    public function removeExamRoomBooking(Notice $notice, Subscription $subscription)
    {
        $subscription->exam_room_booking_id = null;
        if ($subscription->save())
            return $this->show($notice, $subscription->id);
        else
        return response()->json(['error' => true], 400);
    }
}
