<?php

namespace App\Http\Controllers\Candidate;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\UploadFileService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Process\DocumentType;
use App\Models\Process\Subscription;
use Illuminate\Support\Facades\Storage;
use App\Models\Process\EnrollmentSchedule;
use App\Repository\EnrollmentCallRepository;
use App\Repository\EnrollmentProcessRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class EnrollmentProcessController extends Controller
{

    private EnrollmentProcessRepository $enrollmentProcessRepository;
    private UploadFileService $uploadFileService;
    private EnrollmentCallRepository $enrollmentCallRepository;

    public function __construct(
        EnrollmentProcessRepository $enrollmentProcessRepository,
        UploadFileService $uploadFileService,
        EnrollmentCallRepository $enrollmentCallRepository
    ) {
        $this->enrollmentProcessRepository = $enrollmentProcessRepository;
        $this->enrollmentCallRepository = $enrollmentCallRepository;
        $this->uploadFileService = $uploadFileService;
    }

    public function index(Subscription $subscription)
    {
        $calls = $this->enrollmentCallRepository->getCallsBySubscription($subscription);
        $enrollmentProcess = $this->enrollmentProcessRepository->getBySubscription($subscription);
        $sendedDocuments = $this->enrollmentProcessRepository->getSendedDocuments($enrollmentProcess, $subscription);

        $hasPending = $enrollmentProcess->where('status', 'pendente')->first();

        $acceptedTerms = $subscription->acceptedTerms();

        //dd($hasPending);
        if($hasPending && !$acceptedTerms){
            return view('candidate.subscription.enrollment.accept_terms_of_consent', compact('subscription', 'hasPending'));
        }

        $waitingFeedback = false;
        if($hasPending) {
            $waitingFeedback = ($hasPending->send_at)  ? true : false;
        }
        $documentTypes = $this->enrollmentProcessRepository->getDocumentsNeeds($calls->where('status', 'pendente')->first()->affirmative_action_id ?? null, $sendedDocuments->where('enrollment_process_id', $hasPending->id ?? null));
        $uploadMaxSize = $this->uploadFileService->getUploadMaxSizeToView();
        return view('candidate.subscription.enrollment.index', compact(
            'enrollmentProcess',
            'subscription',
            'uploadMaxSize',
            'waitingFeedback',
            'documentTypes',
            'hasPending',
            'sendedDocuments',
            'acceptedTerms'
        ));
    }

    public function viewDocument(Subscription $subscription, int $enrollmentProcess, string $documentId)
    {
        try {
            $enrollmentProcess = $this->enrollmentProcessRepository->getBySubscriptionAndEnrollmentProcessId($subscription, $enrollmentProcess);
            if (!$enrollmentProcess) return abort(400);
            $theDoc = $this->enrollmentProcessRepository->getSendedDocumentByEnrollmentProcessIdAndDocumentUuid($enrollmentProcess->id, $documentId, $subscription);
            if(str_contains($theDoc->mime_type,'image')) {
                $file = base64_encode(Storage::disk('documents')->get($theDoc->path));
                $mimeType = $theDoc->mime_type;
                return view('candidate.subscription.enrollment.view-document',compact('file','mimeType'));
            }
            return response(
                Storage::disk('documents')->get($theDoc->path)
            )->header('Content-Type', $theDoc->mime_type);
        } catch (Exception $exception) {
            Log::error($exception->getMessage(), ['enrollment', 'viewDocument']);
            return abort(500);
        }
    }


    public function store(Request $request, Subscription $subscription)
    {
        //TERMOS DE CONSENTIMENTO - ISSUE 387
        foreach ($request->all() as $k => $v){
            if(key_exists($k, $subscription->getAttributes()) && str_starts_with($k, 'term_')) $subscription->{$k} = $v;
        }
        $subscription->save();
        if(isset($request->terms_of_consent)){
            return redirect()->route('candidate.subscription.enrollment-process.index', ['subscription' => $subscription]);
        }

        //FIM
        $successMessage = 'Uma requisição de matrícula foi iniciada, envie corretamente seus documentos';
        $finished = null;
        if ($request->set_send_at) {
            $finished = Carbon::now();
            $successMessage = 'Legal! Recebemos seu pedido de matrícula, em breve analisaremos! Enquanto isso você ainda pode editar alguns documentos';
        };
        $call = $this->enrollmentCallRepository->getPendingCallBySubscription($subscription);
        $enrollmentProcess = $this->enrollmentProcessRepository->getBySubscription($subscription);
        $hasPending = $enrollmentProcess->where('status', 'pendente')->first();
        $this->enrollmentProcessRepository->makeEnroll($subscription, $call->call_number, $finished);
        // AGENDA NOTIFICAÇÃO POR EMAIL PARA AVALIAÇÃO DO SERVIÇO
        // POR ENQUANTO APENAS AS MATRÍCULAS EM CURSOS SUPERIORES SERÃO AVALIADAS
        if($hasPending && $subscription->notice->modality->id == 3){
            $protocolo = $subscription->subscription_number;
            $cpf = Auth::user()->cpf;
            $cod_servico = env('AVALIACAO_SERVICO_MATRICULA_SUPERIOR');
            try{
                $SR = new ServiceRatingController($protocolo, $cpf, $cod_servico, "🙌 Uhul... também estamos animados com sua matrícula! - Portal do Candidato","Matrícula no Processo Seletivo {$subscription->notice->number} - {$subscription->notice->description}");
                $SR->exec();
            }catch (\Exception $e){
                Log::error($e->getMessage(),['Avaliação do Serviço']);
            }
        }
        //FIM
        return redirect()->route('candidate.subscription.enrollment-process.index', ['subscription' => $subscription])
            ->with('success', $successMessage);
    }


    public function show(Subscription $subscription, $id)
    {
        $call = $this->enrollmentCallRepository->getPendingCallBySubscription($subscription);
        $enrollmentSchedule = EnrollmentSchedule::where('call_number', $call->call_number ?? 0)->enrollmentOpened()->first();
        $today = Carbon::now();
        $hash = md5($subscription->id . ' - vacancy - ' . $today->format('Y-m-d'));
        $url = route('verify.vacancy.certificate', [
            'hash'  => $hash
        ]);
        $html = view('candidate.subscription.enrollment.vacancy-certificate-template', compact(
            'subscription',
            'call',
            'enrollmentSchedule',
            'today',
            'hash',
            'url'
        ))->render();
        Storage::disk('documents')->put("vacancy/$hash.html", $html);
        return view('candidate.subscription.enrollment.vacancy-certificate', compact(
            'subscription',
            'html'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription, $id)
    {
        $enrollmentProcess = $this->enrollmentProcessRepository->getBySubscriptionAndEnrollmentProcessId($subscription, $id);
        $documentType = DocumentType::findOrFail($request->document_type_id);
        $uploadMaxSize = $this->uploadFileService->getUploadMaxSizeInBytes();
        $mimeTypes = 'pdf';
        $request->validate(
            ['document' => [($documentType->required) ? 'required' : 'nullable', 'file', "mimes:$mimeTypes", "max:$uploadMaxSize"]],
            ['document.*' => 'Um erro ocorreu ao enviar o seu arquivo, verifique se o tamanho atende o solicitado e se o formato está em pdf'],
            ['document' => 'Documento']
        );

        DB::beginTransaction();
        try {
            $folder = $subscription->notice->getNoticeSchemaName() . '/' . $subscription->id;
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $uuid = ($request->uuid) ?? Str::uuid();
                $fileName =  $uuid . '.' . $file->getClientOriginalExtension();
                $documentTitle = $documentType->title;
                $path = $folder . '/' . $fileName;
                $document = $this->enrollmentProcessRepository->saveDocs($subscription, $documentType, $enrollmentProcess->id,  $documentTitle, $path, $file, $uuid);
                $file->storeAs($folder, $fileName, 'documents');
            }
            DB::commit();
            return redirect()->route('candidate.subscription.enrollment-process.index', [
                'subscription' => $subscription
            ])->with('success', 'Arquivo enviado com sucesso');
            return response()->json([
                'document' =>  $document
            ], 200);
        } catch (Exception $exception) {
            Log::error("Um erro ao enviar o documento para a inscrição {$subscription->id} na linha " . $exception->getLine() . " " . $exception->getMessage(), ['enrollmentProcess', 'saveDocs']);
            DB::rollBack();
            return response()->json([
                'error' => 'Um erro ocorreu ao enviar o arquivo'
            ], 500);
        }
    }

    public function destroy(Request $request, Subscription $subscription, $id)
    {
        $enrollmentProcess = $this->enrollmentProcessRepository->getBySubscriptionAndEnrollmentProcessId($subscription, $id);
        $uuid = ($request->uuid) ?? Str::uuid();
        $theDoc = $this->enrollmentProcessRepository->getSendedDocumentByEnrollmentProcessIdAndDocumentUuid($enrollmentProcess->id, $uuid, $subscription);
        Storage::disk('documents')->delete($theDoc->path);
        $this->enrollmentProcessRepository->deleteDocumentByUuid($enrollmentProcess->id, $uuid, $subscription);
        return redirect()->route('candidate.subscription.enrollment-process.index', [
            'subscription' => $subscription
        ])->with('success', 'O documento foi excluído com sucesso');
    }
}
