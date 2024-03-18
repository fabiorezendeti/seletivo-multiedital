<?php

namespace App\Http\Controllers\Admin\Enrollment;


use Illuminate\Http\Request;
use App\Models\Process\Notice;
use App\Http\Controllers\Controller;
use App\Http\Requests\CallRequest;
use App\Models\Process\AffirmativeAction;
use App\Models\Process\Offer;
use App\Models\Process\SelectionCriteria;
use App\Repository\EnrollmentCallRepository;
use App\Services\Notice\EnrollmentCallService;
use App\Models\Process\Subscription;
use App\Repository\EnrollmentProcessRepository;
use App\Services\CsvLib\CsvFileService;
use App\Services\CsvLib\Interfaces\CsvWriter;
use App\Services\FpdiService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use STS\ZipStream\ZipStream;
use STS\ZipStream\ZipStreamFacade;
use App\Repository\ParametersRepository;


class CallController extends Controller
{

    private EnrollmentCallService $enrollmentCallService;
    private EnrollmentCallRepository $enrollmentCallRepository;

    public function __construct(
        EnrollmentCallService $enrollmentCallService,
        EnrollmentCallRepository $enrollmentCallRepository
    ) {
        $parameters = new ParametersRepository();
        
        $this->enrollmentCallService = $enrollmentCallService;
        $this->enrollmentCallRepository = $enrollmentCallRepository;
    }

    public function index(Notice $notice)
    {
        $this->enrollmentCallService->boot($notice);
        $callCounting = $this->enrollmentCallRepository->countCallsByNotice($notice);
        return view('admin.notices.enrollment.calls.index', compact(
            'notice',
            'callCounting'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Notice $notice)
    {
        $this->enrollmentCallService->boot($notice);
        $callCounting = $this->enrollmentCallRepository->countCallsByNotice($notice);
        return view('admin.notices.enrollment.calls.create', compact(
            'notice',
            'callCounting'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CallRequest $request, Notice $notice)
    {
        $start_date = $request->enrollment_start_date;
        $end_date = $request->enrollment_end_date;
        $protected_affirmative_actions = $request->protected_affirmative_actions ?? 'N';
        try {
            $this->enrollmentCallService->makeNewCall($notice, $start_date, $end_date, $protected_affirmative_actions);
            return redirect()->route('admin.notices.calls.index', ['notice' => $notice])
                ->with('success', 'A chamada foi realizada, verifique os relatórios');
        } catch (Exception $exception) {
            return redirect()->route('admin.notices.calls.create', ['notice' => $notice])
                ->with('error', "Um erro ocorreu ao realizar chamada {$exception->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Notice $notice, $id)
    {
        $callNumber = $id;
        $selectionCriteria = SelectionCriteria::findOrFail($request->selection_criteria_id);

        if ($request->html) {
            $offer = Offer::find($request->offer);
            $approvedList = $this->enrollmentCallRepository->getApprovedListByOfferAndCriteriaAndCallNumber($offer, $selectionCriteria, $callNumber)->get();
            $view = ($selectionCriteria->id > 2) ? 'admin.notices.enrollment.calls.report-with-media' : 'admin.notices.enrollment.calls.report';
            return view($view, compact(
                'notice',
                'offer',
                'selectionCriteria',
                'callNumber',
                'approvedList'
            ));
        }

        $notice = Notice::with(['offers.courseCampusOffer.campus', 'offers.courseCampusOffer.course'])->findOrFail($notice->id);
        return view('admin.notices.enrollment.calls.show', compact(
            'notice',
            'selectionCriteria',
            'callNumber'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Notice $notice, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CallRequest $request, Notice $notice, $id)
    {
        try {
            $notice->enrollmentSchedule()->updateOrCreate(
                [
                    'call_number'           => $id,
                    'selection_criteria_id' => $request->selection_criteria_id,
                ],
                [
                    'start_date'    => $request->enrollment_start_date,
                    'end_date'    => $request->enrollment_end_date,
                ]
            );
            return redirect()->route('admin.notices.calls.index', ['notice' => $notice])
                ->with('success', 'A data de matrícula foi atualizada com sucesso');
        } catch (QueryException $exception) {
            Log::error($exception->getMessage(), ['updateEnrollmentDates']);
            return redirect()->route('admin.notices.calls.index', ['notice' => $notice])
                ->with('error', 'Um erro ocorreu ao Salvar data de matrícula');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Notice $notice, $call)
    {
        $selectionCriteria = SelectionCriteria::findOrFail($request->selection_criteria_id);
        $tableName = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
        DB::table($tableName)
            ->where('call_number', $call)
            ->delete();
        return redirect()->route('admin.notices.calls.index', ['notice' => $notice])
            ->with('success', "A chamada foi desfeita para {$selectionCriteria->description}");
    }

    /**
     *
     *
     * @param
     * @return
     */
    public function indexToRegister(Request $request, CsvWriter $csvWriter, Notice $notice, $id)
    {         
        $search = $request->search;
        $status = $request->status_search;
        $callNumber = $id;
        $selectionCriteria = SelectionCriteria::findOrFail($request->selection_criteria_id);

        if (Gate::allows('isAdmin')) {
            $offers = $notice->offers->sortBy('courseCampusOffer.campus.name');
        } else if (Gate::allows('isAcademicRegister')) {
            $campuses = Auth::user()->permissions()
                ->select('permissions.campus_id')
                ->where('role_id', 2)
                ->where('user_id', Auth::user()->id)->get();

            $offers = $notice->offers()->whereHas('courseCampusOffer', function ($q) use ($campuses) {
                $q->whereIn('campus_id', $campuses->pluck('campus_id'));
            })->get()->sortBy('courseCampusOffer.campus.name');
        }

        if ($request->offer) {
            $offer = Offer::findorFail($request->offer);
            $approvedList = $this->enrollmentCallRepository->getApprovedListByOfferAndCriteriaAndCallNumberAndSearch($offer, $selectionCriteria, $callNumber, $search, $status);
        } else {
            $offer = new Offer();
            $approvedList = $this->enrollmentCallRepository->getApprovedListByNoticeAndCriteriaAndCallNumberAndSearch($notice, $selectionCriteria, $callNumber, $search, $status);
        }

        if ($request->affirmativeAction) {
            $af = AffirmativeAction::findOrFail($request->affirmativeAction);
            $approvedList = $approvedList->where('aa.slug', $af->slug);
        }


        $notice = Notice::with(['offers.courseCampusOffer.campus', 'offers.courseCampusOffer.course'])->findOrFail($notice->id);

        if ($notice->enrollment_process_enable) {
            $enrollProcessTable = $notice->getEnrollmentProcessTableName();
            $enrollProcessDocuments = $notice->getEnrollmentProcessDocumentsTableName();
            $approvedList->leftJoin("{$enrollProcessTable} as enrollprocess", 'enrollprocess.subscription_id', '=', 'call.subscription_id');
            $approvedList->addSelect('enrollprocess.send_at as send_at');
            
            if ($request->withDocuments === 'Y') {
                $approvedList->whereNotNull('send_at');
            }
            if ($request->withDocuments === 'OPEN') {
                $approvedList->whereNull('send_at');
            }
            if ($request->withDocuments === 'OPEN_WITH_DOCS') {
                $approvedList->whereNull('send_at');
                $approvedList->where(DB::raw("(select count(id) from {$enrollProcessDocuments} where {$enrollProcessDocuments}.enrollment_process_id = enrollprocess.id)"), '>', 0);
            }
            if ($request->withDocuments === 'OPEN_WITHOUT_DOCS') {
                $approvedList->whereNull('send_at');
                $approvedList->where(DB::raw("(select count(id) from {$enrollProcessDocuments} where {$enrollProcessDocuments}.enrollment_process_id = enrollprocess.id)"), '<', 1);
            }
        }

        if ($request->input('contact-report-csv')) {
            $approvedList = $approvedList->get();

            $csvWriter->insertOne(
                ['Inscrição', 'Nome', 'E-mail', 'Telefone', 'Telefone Alternativo', 'Ação Afirmativa', 'Status']
            );
            foreach ($approvedList as $subscription) {
                $csvWriter->insertOne(
                    [
                        'subscription_number'  => $subscription->subscription_number,
                        'name'            => $subscription->user->name,
                        'email'           => $subscription->user->email,
                        'phone_number'    => $subscription->user->contact->phone_number ?? null,
                        'alternative_phone_number' => $subscription->user->contact->alternative_phone_number ?? null,
                        'affirmative_action'    => $subscription->distributionOfVacancy->affirmativeAction->slug,
                        'status'    => $subscription->status
                    ]
                );
            }
            return $csvWriter->output("Relatório-de-Candidatos-Aprovados-Com-Contatos.csv");
        }

        if ($request->input('contact-report')) {
            $approvedList = $approvedList->get();
            return view('admin.notices.enrollment.calls.report-with-contact', compact(
                'notice',
                'selectionCriteria',
                'offers',
                'offer',
                'callNumber',
                'approvedList'
            ));
        }



        $approvedList = $approvedList->paginate();
        return view('admin.notices.enrollment.calls.index-to-register', compact(
            'notice',
            'selectionCriteria',
            'offers',
            'callNumber',
            'approvedList'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSubscription(Request $request, Notice $notice, $id, $approvedId, EnrollmentProcessRepository $enrollmentProcessRepository)
    {
        $callNumber = $id;
        $selectionCriteria = SelectionCriteria::findOrFail($request->selection_criteria_id);
        $subscription = $this->enrollmentCallRepository->getApprovedById($notice, $selectionCriteria, $approvedId);
        $enrollmentProcess = $enrollmentProcessRepository->getBySubscription($subscription, $callNumber);
        $documents = ($enrollmentProcess) ? $enrollmentProcessRepository->getSendedDocumentByEnrollmentProcessId($enrollmentProcess->id, $subscription) : [];
        return response()->json([
            'subscription' => $subscription,
            'enrollmentProcess' => $enrollmentProcess,
            'documents' => $documents
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, Notice $notice, $call, $approvedId)
    {
        $selectionCriteria = SelectionCriteria::findOrFail($request->selection_criteria_id);
        $status = $request->status;
        if ($status == 'pendente' || $status == 'matriculado' || $status == 'não matriculado' || $status == 'pré cadastro') {
            if ($this->enrollmentCallRepository->updateStatus($notice, $selectionCriteria, $approvedId, $status)) {
                return $this->enrollmentCallRepository->getApprovedById($notice, $selectionCriteria, $approvedId)->toJson();
            }
            return response()->json(['error' => true], 204);
        }
        return false;
    }

    public function enrollProcessFeedback(Notice $notice, $call, $enrollmentProcessId, Request $request, EnrollmentProcessRepository $enrollmentProcessRepository)
    {
        try {
            $table = $notice->getEnrollmentProcessTableName();
            $user = Auth::user();
            $now = Carbon::now();
            if (($position = strpos($request->feedback, "#-#")) !== FALSE) {
                $request->feedback = substr($request->feedback, 0, $position);
            }
            DB::table($table)
                ->where('id', $enrollmentProcessId)
                ->update(
                    [
                        'feedback' => ($request->feedback) ? $request->feedback . "\n #-# Este feedback foi emitido por {$user->name} em {$now->format('d/m/Y H:i')}" : null,
                        'feedback_user_id' => Auth::id(),
                        'send_at'   => null
                    ]
                );
            $enrollmentProcess = DB::table($table)
                ->where('id', $enrollmentProcessId)
                ->first();
            return response()->json(['enrollmentProcess' => $enrollmentProcess]);
        } catch (Exception $exception) {
            return response()->json(['error' => true], 500);
        }
    }

    public function enrollProcessDocumentFeedback(Request $request, Notice $notice, $call, $enrollmentProcessId, $documentId, EnrollmentProcessRepository $enrollmentProcessRepository)
    {
        try {
            DB::beginTransaction();
            $table = $notice->getEnrollmentProcessDocumentsTableName();
            $enrollTable = $notice->getEnrollmentProcessTableName();
            $user = Auth::user();
            $now = Carbon::now();
            if ($request->is_valid) {
                $request->feedback = null;
            }
            if (($position = strpos($request->feedback, "#-#")) !== FALSE) {
                $request->feedback = substr($request->feedback, 0, $position);
            }
            DB::table($table)
                ->where('enrollment_process_id', $enrollmentProcessId)
                ->where('id', $documentId)
                ->update(
                    [
                        'feedback' => ($request->feedback) ? $request->feedback . "\n #-# Este feedback foi emitido por {$user->name} em {$now->format('d/m/Y H:i')}" : null,
                        'feedback_user_id' => Auth::id()
                    ]
                );
            DB::table($enrollTable)
                ->where('id', $enrollmentProcessId)
                ->update(['send_at' => null]);
            $documents = DB::table($table)
                ->where('enrollment_process_id', $enrollmentProcessId)
                ->orderBy('document_type_id')
                ->get();
            DB::commit();
            return response()->json([
                'documents' => $documents
            ], 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => true], 500);
        }
    }

    public function enrollProcessDocumentsDownload(
        Notice $notice,
        $call,
        $enrollmentProcessId,
        Subscription $subscription,
        EnrollmentProcessRepository $enrollmentProcessRepository
    ) {
        $docs = $enrollmentProcessRepository->getSendedDocumentByEnrollmentProcessId($enrollmentProcessId, $subscription);
        $path = Storage::disk('documents')->path('');

        $files = [];        
        $name = "inscricao_{$subscription->subscription_number}_chamado_{$call}.zip";
        $zip = ZipStreamFacade::create($name);
        $order = 1;
        foreach ($docs as $doc) {
            $fileName = Str::upper('MAT-'.$subscription->user->name.'-'.$doc->document_title.'-'.$order++);
            $zip->add($path.$doc->path, $fileName.'.pdf');            
        }
        
        return $zip;
    }

    public function enrollProcessDocumentsSignAndDownload(
        Notice $notice,
        $call,
        $enrollmentProcessId,
        Subscription $subscription,
        EnrollmentProcessRepository $enrollmentProcessRepository,
        Request $request,
        FpdiService $fpdiService
    ) { 
        $parameters = new ParametersRepository();
               
        $info = [
            'Name'  => Auth::user()->name,
            'Location'  => $parameters->getValueByName('nome_instituicao_curto'),
            'Reason'    => 'Matrícula',
            'ContactInfo'   => Auth::user()->email
        ];
        $now = Carbon::now();
        openssl_pkcs12_read(file_get_contents($request->certificate->getRealPath()), $theData, $request->password);
        if(!$theData) {
            return 'Seu certificado ou senha está incorretos, revise a senha e tente novamente';
        }                  

        $docs = $enrollmentProcessRepository->getSendedDocumentByEnrollmentProcessId($enrollmentProcessId, $subscription);
        $path = Storage::disk('documents')->path('');
    
        $name = "inscricao_{$subscription->subscription_number}_chamado_{$call}.zip";        
        $zip = ZipStreamFacade::create($name);
        $order = 1;
        foreach ($docs as $doc) {                    
            $fileName = Str::upper('MAT-'.$subscription->user->name.'-'.$doc->document_title.'-'.$order++);
            try {
                $fpdi = $fpdiService->getFpdi();
                $fpdi->setSignature($theData['cert'], $theData['pkey'], $request->password, '', 2, $info);                
                $pageCount = $fpdi->setSourceFile($path.$doc->path);
    
                for ($i = 1; $i <= $pageCount; $i++) {
                    $fpdi->AddPage();
                    $tplId = $fpdi->importPage($i);
                    $fpdi->useTemplate($tplId, 0, 0);
                }                                            
                $fpdi->setSignatureAppearance(0, $fpdi->getPageHeight() - 7, $fpdi->getPageWidth(), 7);                
                $fpdi->setY(-7);
                $fpdi->setAutoPageBreak(false,0);
                $fpdi->SetFont('helvetica', '', 6);
                $fpdi->Cell($fpdi->getPageWidth()-20,7,'Documento Assinado Digitalmente por '. Auth::user()->name . ' - CPF: ' . Auth::user()->cpf . "\n em " . $now->format('d/m/Y H:i:s'), 1 );
                $content = $fpdi->Output(tmpfile(), 'S');
                
                $zip->addRaw($content, $fileName.'.pdf');
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
                $zip->add($path.$doc->path,'NÃO-ASSINADO_'.$fileName.'.pdf');
            }            
        }        
        return $zip;      
    }

    public function changeStatusForCriteria(Notice $notice, $call, SelectionCriteria $selectionCriteria)
    {
        $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
        DB::table("$table as calls")
            ->where('calls.call_number', $call)
            ->where('calls.status', 'pendente')
            ->update([
                'status' => 'matriculado'
            ]);
        return redirect()->route('admin.notices.calls.index', ['notice' => $notice])
            ->with('success', "Todos os pendentes da chamada {$call} do critério {$selectionCriteria->description} tiveram o status alterado ");
    }

    public function enrollExport(Notice $notice, $call, SelectionCriteria $selectionCriteria)
    {
        $callTable = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
        $subscriptions = DB::table("$callTable as call")
            ->select(
                'us.name',
                'us.email',
                'us.cpf',
                'us.rg',
                'us.rg_emmitter',
                'us.social_name',
                'us.mother_name',
                'us.birth_date',
                'us.nationality',
                'us.sex',
                'us.is_foreign',
                'cont.street',
                'cont.number',
                'cont.district',
                'cont.zip_code',
                'cont.phone_number',
                'cont.alternative_phone_number',
                'cont.complement',
                'cit.name as city_name',
                'sta.slug as state_slug',
                'sta.name as state_name'
            )
            ->join('core.subscriptions as subs', 'call.subscription_id', '=', 'subs.id')
            ->join('core.users as us', 'us.id', '=', 'subs.user_id')
            ->join('core.contacts as cont', 'cont.user_id', '=', 'us.id')
            ->join('core.cities as cit', 'cit.id', '=', 'cont.city_id')
            ->join('core.states as sta', 'sta.id', '=', 'cit.state_id')
            ->where('call.status', '=', 'pendente')
            ->get();
        $csvWriter = new CsvFileService(false);
        $csvWriter->setDelimiter(';');
        $csvWriter->insertOne(
            [
                'cpf',
                'nome_oficial',
                'nome_social',
                'email',
                'nome_mae',
                'nome_pai',
                'genero_oficial',
                'genero_social',
                'data_nascimento',
                'estado_civil',
                'cor_raca',
                'tipo_escola_ensino_medio',
                'nome_escola_ensino_medio',
                'ano_conclusao',
                'tipo_sanguineo',
                'rg_numero',
                'rg_orgao_expedidor',
                'rg_uf',
                'rg_data_expedicao',
                'titulo_eleitor_numero',
                'titulo_eleitor_zona',
                'titulo_eleitor_sessao',
                'titulo_eleitor_uf',
                'titulo_eleitor_ddata_expedicao',
                'endereco_cep',
                'endereco_logradouro_tipo',
                'endereco_logradouro_nome',
                'endereco_numero',
                'endereco_complemento',
                'endereco_bairro',
                'endereco_cidade',
                'endereco_estado',
                'telefone_fixo',
                'telefone_celular',
            ]
        );
        foreach ($subscriptions as $subscription) {
            $csvWriter->insertOne(
                [
                    preg_replace("/\.|\-/", '', $subscription->cpf),
                    $subscription->name,
                    $subscription->social_name,
                    $subscription->email,
                    $subscription->mother_name,
                    null,
                    $subscription->sex ?? 'M',
                    null,
                    $subscription->birth_date,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $subscription->rg,
                    'SSP',
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $subscription->zip_code,
                    1,
                    $subscription->street,
                    $subscription->number,
                    $subscription->complement,
                    $subscription->district,
                    $subscription->city_name,
                    $subscription->state_slug,
                    $subscription->alternative_phone_number ?? $subscription->phone_number,
                    $subscription->phone_number,
                ]
            );
        }
        return $csvWriter->output("Candidatos_matriculados_chamada_{$call}_criterio_{$selectionCriteria->description}.csv");
    }
}
