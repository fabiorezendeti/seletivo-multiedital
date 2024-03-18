<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\User\ContactController;
use App\Http\Controllers\Admin\ModalityController;
use App\Http\Controllers\Dev\MaintenanceController;
use App\Http\Controllers\Admin\LotteryDrawController;
use App\Http\Controllers\Admin\NoticeOfferController;
use App\Http\Controllers\Admin\ReadGruFileController;
use App\Http\Controllers\Admin\SpecialNeedController;
use App\Http\Controllers\Admin\NoticeMailSendController;
use App\Http\Controllers\Admin\ClassificationsController;
use App\Http\Controllers\Admin\Enrollment\CallController;
use App\Http\Controllers\Admin\NoticeRecoursesController;
use App\Http\Controllers\Candidate\SubscriptionController;
use App\Http\Controllers\Admin\AdditionalTestTimeController;
use App\Http\Controllers\Admin\AffirmativeActionsController;
use App\Http\Controllers\Admin\AnswerCardController;
use App\Http\Controllers\Admin\MigrationVacancyMapController;
use App\Http\Controllers\Admin\NoticeSubscriptionsController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\ExamLocationsController;
use App\Http\Controllers\Admin\ExamResourceAnalysisController;
use App\Http\Controllers\Admin\ExamResourceController;
use App\Http\Controllers\Admin\NoticePaymentExemptionsController;
use App\Http\Controllers\Admin\ExamRoomsController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\AllocationOfExamRoomBookingController;
use App\Http\Controllers\Admin\ImportSISUController;
use App\Http\Controllers\Admin\PagtesouroSettingsController;
use App\Http\Controllers\Admin\ApiSettingsController;
use App\Http\Controllers\Candidate\NoticeController as CandidateNoticeController;
use App\Http\Controllers\Candidate\DocumentController as CandidateDocumentController;
use App\Http\Controllers\Candidate\EnrollmentProcessController;
use App\Http\Controllers\Candidate\PaymentExemptionController;
use App\Http\Controllers\Candidate\PagTesouroController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\DocVerifyController;
use App\Http\Controllers\LoginUnicoController;
use App\Http\Controllers\Admin\AnswerTemplatesController;
use App\Http\Controllers\Admin\ExamRoomBookingsController;
use App\Http\Controllers\PublicReportController;

Route::get('/', function () {
    if(Auth::check()){
        return redirect()->route('dashboard');
    }
    return view('candidate.welcome');
})->name('welcome');

//ÁREA PÚBLICA
Route::any('public/report/selection-result', [PublicReportController::class, 'selection_result'])->name('relatorio-processo-seletivo');
//

// LOGIN ÚNICO HABILITADO
if (env('LOGIN_UNICO_ENABLE')) {
    Route::get('/login/redirect', [LoginUnicoController::class, 'redirect'])->name('login_url');
    Route::get('/login/callback', [LoginUnicoController::class, 'callback']);
    Route::post('/register', [LoginUnicoController::class, 'store'])->middleware(['guest:'.config('fortify.guard')]);
    Route::any('/logout', [LoginUnicoController::class, 'logout'])->name('logout');
}

if (!function_exists('boletimLinks')) {
    function boletimLinks($admin = true)
    {
        Route::get('notices/{notice}/subscription/{subscription}/view-document', [AdminDocumentController::class, 'viewBoletim'])
            ->name('viewBoletim');
    }
}

/*
 * ROTAS PARA ADMINISTRAÇÃO DE SALAS DO EDITAL SELECIONADO
 */
if (!function_exists('allocationRoomLinks')) {
    function allocationRoomLinks($admin = true)
    {
        Route::name('notices.allocation-of-exam-room.')
            ->group(function () use ($admin) {
                $middleware = ($admin) ? 'can:isAdmin' : 'can:hasOfferInMyCampuses,notice';
                if ($admin) {
                    Route::get(
                        'notices/{notice}/allocation-of-exam-room',
                        [AllocationOfExamRoomBookingController::class, 'index']
                    )->middleware('can:allowAllocateExamRoom,notice')
                        ->name('index');

                    Route::post(
                        'notices/{notice}/allocation-of-exam-room/auto-allocate',
                        [AllocationOfExamRoomBookingController::class, 'autoAllocate']
                    )
                        ->middleware(['can:allowAutoAllocateExamRoom,notice','can:examIsNotPast,notice'])
                        ->name('auto-allocate');

                    Route::delete(
                        'notices/{notice}/allocation-of-exam-room/undo-auto-allocate',
                        [AllocationOfExamRoomBookingController::class, 'undoAutoAllocate']
                    )
                        ->middleware(['can:allowAllocateExamRoom,notice','can:examIsNotPast,notice'])
                        ->name('undo-auto-allocate');

                    Route::get(
                        'notices/{notice}/allocation-of-exam-room/report',
                        [AllocationOfExamRoomBookingController::class, 'report']
                    )
                        ->name('report');
                    Route::get(
                        'notices/{notice}/allocation-of-exam-room/manual',
                        [AllocationOfExamRoomBookingController::class, 'indexManual']
                    )->middleware([$middleware,'can:examIsNotPast,notice'])
                        ->name('manual');

                    Route::get('notices/{notice}/allocation-of-exam-room/subscriptions/{subscription}', [
                        NoticeSubscriptionsController::class, 'show'
                    ])->middleware($middleware)
                        ->name('subscriptions.show');
                    Route::put('notices/{notice}/allocation-of-exam-room/subscriptions/{subscription}/update-exam-room', [
                        NoticeSubscriptionsController::class, 'updateExamRoomBooking'
                    ])->middleware([$middleware,'can:examIsNotPast,notice'])
                        ->name('subscriptions.update-exam-room-booking');

                    Route::delete('notices/{notice}/allocation-of-exam-room/subscriptions/{subscription}/remove-exam-room', [
                        NoticeSubscriptionsController::class, 'removeExamRoomBooking'
                    ])->middleware([$middleware,'can:examIsNotPast,notice'])
                        ->name('subscriptions.remove-exam-room-booking');
                    Route::get('notices/{notice}/allocation-of-exam-room/exam-location/{exam_location}', [
                        ExamRoomBookingsController::class, 'show'
                    ])->middleware($middleware)
                        ->name('exam_location');

                    Route::post('notices/{notice}/allocation-of-exam-room/import', [
                        AllocationOfExamRoomBookingController::class, 'importExamRooms'
                    ])->middleware([$middleware,'can:examIsNotPast,notice'])
                        ->name('exam_location.import');

                    Route::get('notices/{notice}/allocation-of-exam-room/exam-location/{exam_location}/exam-rooms-booking/create', [
                        ExamRoomBookingsController::class, 'create'
                    ])->middleware([$middleware,'can:examIsNotPast,notice'])
                        ->name('exam_location.exam_room_booking.create');

                    Route::get('notices/{notice}/allocation-of-exam-room/exam-location/{exam_location}/exam-rooms-booking/{exam_room_booking}/edit', [
                        ExamRoomBookingsController::class, 'edit'
                    ])->middleware([$middleware,'can:examIsNotPast,notice'])
                        ->name('exam_location.exam_room_booking.edit');

                    Route::post('notices/{notice}/allocation-of-exam-room/exam-location/{exam_location}/exam-rooms-booking/store', [
                        ExamRoomBookingsController::class, 'store'
                    ])->middleware([$middleware,'can:examIsNotPast,notice'])
                        ->name('exam_location.exam_room_booking.store');

                    Route::put('notices/{notice}/allocation-of-exam-room/exam-location/{exam_location}/exam-rooms-booking/{exam_room_booking}/update', [
                        ExamRoomBookingsController::class, 'update'
                    ])->middleware([$middleware,'can:examIsNotPast,notice'])
                        ->name('exam_location.exam_room_booking.update');

                    Route::delete('notices/{notice}/allocation-of-exam-room/exam-location/{exam_location}/exam-rooms-booking/{exam_room_booking}/destroy', [
                        ExamRoomBookingsController::class, 'destroy'
                    ])->middleware([$middleware,'can:examIsNotPast,notice'])
                        ->name('exam_location.exam_room_booking.destroy');

                    Route::post('notices/{notice}/allocation-of-exam-room/exam-location/{exam_location}/report-by-room-booking-short', [
                        ExamLocationsController::class, 'reportRoomBookingShort'
                    ])->middleware($middleware)
                        ->name('exam-location.report-by-room-booking-short');

                    Route::post('notices/{notice}/allocation-of-exam-room/exam-location/{exam_location}/report-by-room-booking', [
                        ExamLocationsController::class, 'reportRoomBooking'
                    ])->middleware($middleware)
                        ->name('exam-location.report-by-room-booking');
                }
            });
    }
}
//Fim

if (!function_exists('ppiLinks')) {
    function ppiLinks($admin = true)
    {
        $middleware = ($admin) ? 'can:isAdmin' : 'can:hasOfferInMyCampuses,notice';

        Route::get('notices/{notice}/subscriptions/ppi/download', [NoticeSubscriptionsController::class, 'downloadPPIWithContact'])
            ->middleware($middleware)
            ->name('notices.subscriptions.ppi.download');

        Route::get('notices/{notice}/subscriptions/ppi', [NoticeSubscriptionsController::class, 'indexPPI'])
            ->middleware($middleware)
            ->name('notices.subscriptions.ppi');


        Route::put('notices/{notice}/subscriptions/{subscription}/checkPPI', [
            NoticeSubscriptionsController::class, 'checkPPI'
        ])
            ->middleware('can:allowAfericaoPPI,subscription')
            ->name('notices.subscriptions.checkPPI');

        Route::put('notices/{notice}/subscriptions/{subscription}/uncheckPPI', [
            NoticeSubscriptionsController::class, 'uncheckPPI'
        ])
            ->middleware('can:allowAfericaoPPI,subscription')
            ->name('notices.subscriptions.uncheckPPI');
    }
}

if (!function_exists('subscriptionNoticeLinks')) {
    function subscriptionNoticeLinks($admin = true)
    {
        Route::resource('notices.subscriptions', NoticeSubscriptionsController::class)
            ->only(['index', 'show']);
    }
}

if (!function_exists('enrollmentLinks')) {
    function enrollmentLinks($admin = true)
    {
        $middleware = ($admin) ? 'can:isAdmin' : 'can:hasOfferInMyCampuses,notice';

        Route::put('notices/{notice}/calls/{call}/enrollment-process/{enrollmentProcessId}/document/{documentId}', [
            CallController::class, 'enrollProcessDocumentFeedback'
        ])
            ->middleware($middleware)
            ->name('notices.calls.enrollment.document.feedback');

        Route::post('notices/{notice}/calls/{call}/enrollment-process/{enrollmentProcessId}/feedback', [
            CallController::class, 'enrollProcessFeedback'
        ])
            ->middleware($middleware)
            ->name('notices.calls.enrollment.feedback');

        Route::get('notices/{notice}/calls/{call}/enrollment-process/{enrollmentProcessId}/download-documents/{subscription}', [
            CallController::class, 'enrollProcessDocumentsDownload'
        ])
            ->middleware($middleware)
            ->name('notices.calls.enrollment.document.download');

        Route::post('notices/{notice}/calls/{call}/enrollment-process/{enrollmentProcessId}/sign-and-download/{subscription}', [
            CallController::class, 'enrollProcessDocumentsSignAndDownload'
        ])
            ->middleware($middleware)
            ->name('notices.calls.enrollment.document.sign-and-download');
    }
}

if (!function_exists('noticeLinks')) {
    function noticeLinks($admin = true)
    {
        if ($admin) {
            Route::resource('notices.calls', CallController::class);
            Route::resource('notices', AdminNoticeController::class);
        } else {
            Route::resource('notices.calls', CallController::class)->only(['index']);
            Route::resource('notices', AdminNoticeController::class)->only(['index', 'show']);
        }
    }
}

if (!function_exists('approvedLinks')) {
    function approvedLinks($admin = true)
    {
        $middleware = ($admin) ? 'can:isAdmin' : 'can:hasOfferInMyCampuses,notice';
        Route::get('notices/{notice}/calls/{call}/approved/{approved}', [
            CallController::class, 'showSubscription'
        ])
            ->middleware($middleware)
            ->name('notices.calls.showSubscription');

        Route::put('notices/{notice}/calls/{call}/approved/{approved}/register', [
            CallController::class, 'register'
        ])
            ->middleware($middleware)
            ->name('notices.calls.register');
    }
}


if (!function_exists('registerLinks')) {
    function registerLinks($admin = true)
    {
        $middleware = ($admin) ? 'can:isAdmin' : 'can:hasOfferInMyCampuses,notice';
        Route::get('notices/{notice}/calls/{call}/register', [
            CallController::class, 'indexToRegister'
        ])
            ->middleware($middleware)
            ->name('notices.calls.indexToRegister');
    }
}

if (!function_exists('updateSubscriptionsLinks')) {
    function updateSubscriptionsLinks($admin = true)
    {
        $middleware = ($admin) ? 'can:isAdmin' : 'can:hasOfferInMyCampuses,notice';
        Route::put('notices/{notice}/subscriptions/{subscription}/eliminate', [
            NoticeSubscriptionsController::class, 'eliminate'
        ])
            ->middleware($middleware)
            ->name('notices.subscriptions.eliminate');

        Route::put('notices/{notice}/subscriptions/{subscription}/update-mean', [
            NoticeSubscriptionsController::class, 'updateMean'
        ])
            ->middleware($middleware)
            ->name('notices.subscriptions.update-mean');
    }
}

Route::get("verify/vacancy/{hash}", [DocVerifyController::class, 'vacancy'])->name('verify.vacancy.certificate');

Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('contact', [ContactController::class, 'edit'])->name('contact.edit'); // route('user.contact.edit')
        Route::put('contact', [ContactController::class, 'update'])->name('contact.update');
    });

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::middleware(['auth:sanctum', 'verified', 'can:managerArea'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/', [AdminNoticeController::class, 'indexCards'])->name('index');
    });

Route::middleware(['auth:sanctum', 'verified', 'can:isAcademicRegisterOrAdmin'])
    ->prefix('admin/process')
    ->name('admin.process.')
    ->group(function () {
        Route::resource('exam-locations', ExamLocationsController::class)
            ->except(['index', 'show'])
            ->middleware(['can:isAdmin']);
        Route::resource('exam-locations', ExamLocationsController::class)
            ->only(['index', 'show']);
        Route::post(
            'exam-locations/{exam_location}/report',
            [ExamLocationsController::class, 'report']
        )
            ->middleware(['can:isAdmin'])
            ->name('exam-locations.report');
        Route::post(
            'exam-locations/{exam_location}/report-by-room',
            [ExamLocationsController::class, 'reportByRoom']
        )
            ->middleware(['can:isAdmin'])
            ->name('exam-locations.report-by-room');
        Route::resource('exam-locations.exam-rooms', ExamRoomsController::class)
            ->middleware(['can:isAdmin']);
    });

Route::middleware(['auth:sanctum', 'verified', 'can:isAdmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('affirmative-actions.migration-vacancy-map', MigrationVacancyMapController::class)
            ->only(['index', 'store']);
        Route::resource('affirmative-actions', AffirmativeActionsController::class)->except(['show']);
        Route::resource('document-types', DocumentTypeController::class);
        Route::resource('campuses', CampusController::class);
        Route::resource('courses', CourseController::class);
        Route::resource('modalities', ModalityController::class)->except(['show']);
        Route::resource('special-needs', SpecialNeedController::class)->except(['show']);
        Route::resource('exam-resources', ExamResourceController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['create', 'show']);
        Route::resource('pagtesouro-settings', PagtesouroSettingsController::class);

        Route::resource('api-settings', ApiSettingsController::class)->except(['update']);
        Route::put('api-settings/update', [ApiSettingsController::class, 'update'])
        ->name('api-settings.update');

        Route::put(
            'notices/{notice}/distribute-lottery-number',
            [AdminNoticeController::class, 'distributeLotteryNumber']
        )
            ->middleware('can:distributeLotteryNumber,notice')
            ->name('notices.distribute-lottery-number');

        Route::name('notices.lottery-draw.')
            ->group(function () {
                Route::get(
                    'notices/{notice}/lottery-draw',
                    [LotteryDrawController::class, 'index']
                )->middleware('can:lotteryDrawAvailable,notice')
                    ->name('index');
                Route::get(
                    'notices/{notice}/lottery-draw/offer/{offer}',
                    [LotteryDrawController::class, 'draw']
                )
                    ->name('make');
                Route::get(
                    'notices/{notice}/lottery-draw/offer/{offer}/report/classification',
                    [LotteryDrawController::class, 'classificationReport']
                )
                    ->name('classification-report');
                Route::post(
                    'notices/{notice}/lottery-draw/offer/{offer}',
                    [LotteryDrawController::class, 'store']
                )
                    ->middleware('can:lotteryDrawAvailable,offer')
                    ->name('store');
                Route::delete(
                    'notices/{notice}/lottery-draw/offer/{offer}',
                    [LotteryDrawController::class, 'destroy']
                )
                    ->middleware('can:doesntHaveLotteryCalls,offer')
                    ->name('destroy');
            });

        Route::name('notices.classifications.')
            ->group(function () {
                Route::get(
                    'notices/{notice}/classifications',
                    [ClassificationsController::class, 'index']
                )->middleware('can:classificationReportAvailables,notice')
                    ->name('index');
                Route::get(
                    'notices/{notice}/classifications/offer/{offer}/reportByCriteria/{selectionCriteria}',
                    [ClassificationsController::class, 'classificationReportByCriteria']
                )->middleware('can:classificationReportAvailables,notice')
                    ->name('report-by-criteria');;
                Route::post(
                    'notices/{notice}/classifications',
                    [ClassificationsController::class, 'store']
                )->middleware('can:classificationAvailable,notice')
                    ->name('store');
                Route::delete(
                    'notices/{notice}/classifications',
                    [ClassificationsController::class, 'destroy']
                )->middleware('can:undoClassificationAvailable,notice')
                    ->name('destroy');
            });

        Route::get('notice/{notice}/mail-send', [NoticeMailSendController::class, 'mailEditor'])
            ->name('notices.mail-send.edit');
        Route::post('notice/{notice}/mail-send', [NoticeMailSendController::class, 'mailSender'])
            ->name('notices.mail-send.sender');

        noticeLinks();

        Route::resource('notices.read-gru-file', ReadGruFileController::class)->only(['index', 'store']);

        Route::get('dashboard-admin/', [DashboardAdminController::class, 'index'])
        ->name('dashboard-admin.index');

        Route::get('/notice/{notice}/sisu/import-offer', [ImportSISUController::class, 'index'])
            ->name('notices.offers.import.index');
        Route::post('/notice/{notice}/sisu/import-offer', [ImportSISUController::class, 'store'])
            ->name('notices.offers.import.store');

        Route::resource('notices.offers', NoticeOfferController::class)->except(['index', 'show']);

        /*gabaritos*/
        Route::get('/notice/{notice}/exams', [ExamController::class, 'index'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.index');
        Route::get('notice/{notice}/exams/create', [ExamController::class, 'create'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.create');
        Route::post('notice/{notice}/exams/create', [ExamController::class, 'store'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.store');
        Route::get('notice/{notice}/exam/{exam}/edit', [ExamController::class, 'edit'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.edit');
        Route::put('notice/{notice}/exam/{exam}/edit', [ExamController::class, 'update'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.update');
        Route::delete('notice/{notice}/exam/{exam}', [ExamController::class, 'destroy'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.destroy');
        Route::get('notice/{notice}/exam/{exam}/answers', [AnswerTemplatesController::class, 'index'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.answers.index');
        Route::post('notice/{notice}/exam/{exam}/answers/create', [AnswerTemplatesController::class, 'store'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.answers.store');
        Route::get('notice/{notice}/exam/{exam}/answer/{answer}/edit', [AnswerTemplatesController::class, 'edit'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.answers.edit');
        Route::put('notice/{notice}/exam/{exam}/answer/{answer}/edit', [AnswerTemplatesController::class, 'update'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.answers.update');
        Route::delete('notice/{notice}/exam/{exam}/answer/{answer}', [AnswerTemplatesController::class, 'destroy'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.answers.destroy');
        Route::get('notice/{notice}/exam/{exam}/answer/{answer}/cancel', [AnswerTemplatesController::class, 'cancelAnswer'])
            ->middleware(['can:isAdmin'])
            ->name('notices.exams.answers.cancel');

        /** Leitura do Cartão de Respostas */
        Route::resource('notice.readanswercard', AnswerCardController::class);
        Route::get('notice/{notice}/fakegenerate', [AnswerTemplatesController::class, 'fakeGenerate'])
            ->middleware(['can:isAdmin'])
            ->name('notices.readanswercard.fakegenerate');

        ppiLinks();

        Route::resource('notices.recourses', NoticeRecoursesController::class, [
            'parameters' => [
                'recourse' => 'subscription'
            ]
        ]);

        Route::get(
            'notices/{notice}/additional-test-time/report',
            [AdditionalTestTimeController::class, 'report']
        )
            ->name('notices.additional-test-time.report');

        Route::resource('notices.additional-test-time', AdditionalTestTimeController::class, [
            'parameters' => [
                'recourse' => 'subscription'
            ]
        ]);

        Route::get(
            'notices/{notice}/exam-resources-analysis/report',
            [ExamResourceAnalysisController::class, 'report']
        )
            ->name('notices.exam-resources-analysis.report');
        Route::resource('notices.exam-resources-analysis', ExamResourceAnalysisController::class, [
            'parameters' => [
                'exam_resource_analysis' => 'subscription'
            ]
        ]);

        Route::resource('notices.calls', CallController::class);

        Route::put('notices/{notice}/calls/{call}/change-status-for-criteria/{selectionCriteria}', [
            CallController::class, 'changeStatusForCriteria'
        ])->name('notices.calls.change-status-for-criteria');

        Route::get('notices/{notice}/calls/{call}/enroll-export/{selectionCriteria}', [
            CallController::class, 'enrollExport'
        ])
            ->name('notices.calls.enrollExport');

        registerLinks();

        approvedLinks();

        enrollmentLinks();

        Route::put('notices/{notice}/subscriptions/{subscription}/homologate', [
            NoticeSubscriptionsController::class, 'homologate'
        ])->name('notices.subscriptions.homologate');

        Route::put('notices/{notice}/subscriptions/homologate-in-batch', [
            NoticeSubscriptionsController::class, 'homologateInBatch'
        ])->name('notices.subscriptions.homologate-in-batch');

        Route::put('notices/{notice}/subscriptions/revoke-homologate-in-batch', [
            NoticeSubscriptionsController::class, 'revokeHomologateInBatch'
        ])->name('notices.subscriptions.revoke-homologate-in-batch');

        updateSubscriptionsLinks();

        Route::get('notices/{notice}/subscriptions/{subscription}/tracking-info', [
            NoticeSubscriptionsController::class, 'trackingInfo'
        ])->name('notices.subscriptions.tracking-info');
        Route::put('notices/{notice}/subscriptions/{subscription}/cancel', [
            NoticeSubscriptionsController::class, 'cancel'
        ])->name('notices.subscriptions.cancel');

        subscriptionNoticeLinks();

        boletimLinks();
        /**
         * Essa rota ficará comentada, habilitaremos quando criarmos o customizador personalizado, se necessário
         * Route::resource('notices.criterias', CriteriaCustomizationController::class);
         */

        allocationRoomLinks();

        Route::get(
            'notices/{notice}/contacts/report',
            [ReportController::class, 'candidateContacts']
        )
            ->name('notices.contact.report');

        Route::get(
            'notices/{notice}/summary/report',
            [ReportController::class, 'summary']
        )
            ->name('notices.summary.report');

        Route::get(
            'notices/{notice}/subscriptions-approveds/report',
            [ReportController::class, 'subscriptionsApproveds']
        )
            ->name('notices.subscriptionsApproveds.report');

        Route::get(
            'notices/{notice}/total-subscriptions/report',
            [ReportController::class, 'totalSubscriptions']
        )
            ->name('notices.totalSubscriptions.report');

        Route::get(
            'notices/{notice}/total-by-calls/report',
            [ReportController::class, 'totalbyCalls']
        )
            ->name('notices.total-by-calls.report');

        Route::get(
            'notices/{notice}/total-by-selection-criterias/report',
            [ReportController::class, 'totalBySelectionCriterias']
        )
            ->name('notices.totalBySelectionCriterias.report');

        Route::get(
            'notices/{notice}/total-by-affirmative-actions/report',
            [ReportController::class, 'totalByAffirmativeActions']
        )
            ->name('notices.totalByAffirmativeActions.report');

        Route::get(
            'notices/{notice}/candidates-vacancies/report',
            [ReportController::class, 'candidatesForVacancies']
        )
            ->name('notices.candidatesForVacancies.report');

        Route::get(
            'notices/{notice}/total-candidates-by-cities/report',
            [ReportController::class, 'totalCandidatesByCities']
        )
            ->name('notices.totalCandidatesByCities.report');

        Route::get(
            'notices/{notice}/affirmative-actions-ppi/report',
            [ReportController::class, 'candidateAffirmativeActionsPPI']
        )
            ->name('notices.affirmative-actions-ppi.report');

        Route::get(
            'notices/{notice}/preliminary-classification-recourse/report',
            [ReportController::class, 'preliminaryClassificationRecourse']
        )
            ->name('notices.preliminaryClassificationRecourse.report');

        Route::get(
            'notices/{notice}/check-ppi/report',
            [ReportController::class, 'checkPPI']
        )
            ->name('notices.check-ppi.report');

        Route::get(
            'notices/{notice}/distributed-lottery-number/report',
            [ReportController::class, 'distributedLotteryNumber']
        )
            ->middleware('can:lotteryDrawAvailable,notice')
            ->name('notices.distributed-lottery-number.report');

        Route::get(
            'notices/{notice}/registered-candidates/report',
            [ReportController::class, 'registeredCandidates']
        )
            ->name('notices.registered-candidates.report');

        Route::get(
            'notices/{notice}/candidates-address/report',
            [ReportController::class, 'candidatesAddress']
        )
            ->name('notices.candidates-address.report');

        Route::get(
            'notices/{notice}/candidates-with-scores/report',
            [ReportController::class, 'candidatesWithScores']
        )
            ->name('notices.candidates-with-scores.report');

        Route::get(
            'notices/{notice}/foreigns/report',
            [ReportController::class, 'foreigns']
        )
            ->name('notices.foreigns.report');

        Route::get(
            'notices/{notice}/candidates-with-social-name/report',
            [ReportController::class, 'candidatesWithSocialNome']
        )
            ->name('notices.candidates-with-social-name.report');

        Route::resource('notices.payment-exemptions', NoticePaymentExemptionsController::class)
            ->only(['index']);

        Route::get(
            'notices/{notice}/payment-exemptions/report',
            [NoticePaymentExemptionsController::class, 'report']
        )
            ->name('notices.payment-exemptions.report');

        Route::get(
            'notices/{notice}/pending-payments/report',
            [ReportController::class, 'pendingPayments']
        )
            ->name('notices.pending-payments.report');

        Route::get(
            'notices/{notice}/candidates-aged-18/report',
            [ReportController::class, 'candidatesAged18']
        )
            ->name('notices.candidates-aged-18.report');

        Route::get(
            'notices/{notice}/subscriptions/{subscription}/payment-exemption/',
            [
                NoticePaymentExemptionsController::class, 'show'
            ]
        )->name('notices.subscriptions.payment-exemption.show');
        Route::put(
            'notices/{notice}/subscriptions/{subscription}/payment-exemption/accept',
            [
                NoticePaymentExemptionsController::class, 'accept'
            ]
        )->name('notices.subscriptions.payment-exemption.accept');
        Route::put(
            'notices/{notice}/subscriptions/{subscription}/payment-exemption/reject',
            [
                NoticePaymentExemptionsController::class, 'reject'
            ]
        )->name('notices.subscriptions.payment-exemption.reject');
        Route::put(
            'notices/{notice}/subscriptions/{subscription}/payment-exemption/update-personal-data',
            [
                NoticePaymentExemptionsController::class, 'updatePersonalData'
            ]
        )->name('notices.subscriptions.payment-exemption.update-personal-data');
        Route::get(
            'notices/{notice}/subscriptions/{subscription}/payment-exemption/view-txt',
            [NoticePaymentExemptionsController::class, 'viewTxt']
        )
            ->name('notices.subscriptions.payment-exemption.viewTxt');
        Route::get(
            'notices/{notice}/subscriptions/{subscription}/payment-exemption/view-id-front',
            [NoticePaymentExemptionsController::class, 'viewDocumentIdFront']
        )
            ->name('notices.subscriptions.payment-exemption.viewDocumentIdFront');
        Route::get(
            'notices/{notice}/subscriptions/{subscription}/payment-exemption/view-id-back',
            [NoticePaymentExemptionsController::class, 'viewDocumentIdBack']
        )
            ->name('notices.subscriptions.payment-exemption.viewDocumentIdBack');
        Route::get(
            'notices/{notice}/subscriptions/{subscription}/payment-exemption/view-form',
            [NoticePaymentExemptionsController::class, 'viewDocumentForm']
        )
            ->name('notices.subscriptions.payment-exemption.viewDocumentForm');

        Route::post('pagtesouro-settings/payment/', [PagTesouroSettingsController::class, 'noStore'])
            ->name('pagtesouro-settings.payment.noStore');

        Route::post('pagtesouro-settings/payment/{paymentRequest}', [PagTesouroSettingsController::class, 'show'])
            ->name('pagtesouro-settings.payment.show');

        Route::post('pagtesouro-settings/payment-status', [PagTesouroSettingsController::class, 'viewPaymentStatus'])
            ->name('pagtesouro-settings.payment.payment-status');

        Route::get('notices/{notice}/pagtesouro-settings/update-payment-status', [PagTesouroSettingsController::class, 'updatePaymentStatus'])
            ->name('pagtesouro-settings.payment.update-payment-status');

        Route::post('pagtesouro-settings/config/edit', [PagTesouroSettingsController::class, 'edit'])
            ->name('pagtesouro-settings.config.edit');
        Route::put('pagtesouro-settings/config/edit', [PagTesouroSettingsController::class, 'update'])
            ->name('pagtesouro-settings.config.update');
    });

Route::middleware(['auth:sanctum', 'verified', 'can:isAcademicRegisterOrPPICommitte'])
    ->prefix('cra')
    ->name('cra.')
    ->group(function () {
        noticeLinks(false);
        ppiLinks(false);
        subscriptionNoticeLinks(false);
    });

Route::middleware(['auth:sanctum', 'verified', 'can:isAcademicRegister'])
    ->prefix('cra')
    ->name('cra.')
    ->group(function () {

        boletimLinks(false);

        registerLinks(false);

        allocationRoomLinks(false);
        Route::put('notices/{notice}/subscriptions/{subscription}/homologate', [
            NoticeSubscriptionsController::class, 'homologate'
        ])->name('notices.subscriptions.homologate');


        Route::put('notices/{notice}/subscriptions/{subscription}/cancel', [
            NoticeSubscriptionsController::class, 'cancel'
        ])->name('notices.subscriptions.cancel');
        updateSubscriptionsLinks(false);

        approvedLinks(false);

        enrollmentLinks(false);
    });

Route::middleware(['auth:sanctum', 'verified'])
    ->middleware('can:subscriptionsIsOpen,notice')
    ->get('/notice/{notice}', [CandidateNoticeController::class, 'show'])->name('notice.show');

Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('candidate')
    ->name('candidate.')
    ->group(function () {
        Route::get('notice/{notice}/offer/{offer}/subscription/create', [SubscriptionController::class, 'create'])
            ->middleware('can:subscriptionsIsOpen,notice')
            ->name('subscription.create');

        Route::post('notice/{notice}/offer/{offer}/subscription', [SubscriptionController::class, 'store'])
            ->middleware('can:subscriptionsIsOpen,notice')
            ->name('subscription.store');

        Route::get('subscription/{subscription}/ticket', [SubscriptionController::class, 'show'])
            ->middleware('can:isMySubscription,subscription')
            ->name('subscription.show');

        Route::post('subscription/{subscription}/request-recourse', [SubscriptionController::class, 'requestRecourse'])
            ->middleware('can:isMySubscription,subscription', 'can:allowRequestPreliminaryClassificationRecourse,subscription')
            ->name('subscription.request-recourse');

        Route::get('subscription/{subscription}/print-exam-location-room', [SubscriptionController::class, 'printExamLocationRoom'])
            ->middleware('can:isMySubscription,subscription')
            ->name('subscription.print-exam-location-room');

        Route::post('subscription/{subscription}/show-interest', [SubscriptionController::class, 'showInterest'])
            ->middleware('can:isMySubscription,subscription', 'can:allowShowInterest,subscription')
            ->name('subscription.show-interest');

        Route::get('subscription/{subscription}/ticket/view-document', [CandidateDocumentController::class, 'viewBoletim'])
            ->middleware('can:isMySubscription,subscription')
            ->name('viewBoletim');

        Route::resource('subscription.payment-exemption', PaymentExemptionController::class)
            ->middleware('can:allowRequestPaymentExemption,subscription')
            ->only(['create', 'store']);


        Route::get('subscription/{subscription}/enrollment-process/{enrollment_process}/view-documents/{documentId}', [EnrollmentProcessController::class, 'viewDocument'])
            ->name('subscription.enrollment-process.view-document')
            ->middleware('can:viewEnrollmentDocuments,subscription');

        Route::resource('subscription.enrollment-process', EnrollmentProcessController::class)
            ->middleware(['can:allowEnrollmentProcess,subscription'])
            ->except(['index', 'create']);

        Route::resource('subscription.enrollment-process', EnrollmentProcessController::class)
            ->middleware(['can:viewEnrollmentProcess,subscription'])
            ->only(['index']);

        Route::get('subscription/{subscription}/payment-exemption/', [PaymentExemptionController::class, 'show'])
            ->middleware('can:isMySubscription,subscription')
            ->name('subscription.payment-exemption.show');

        Route::get('subscription/{subscription}/payment-exemption/view-id-front', [PaymentExemptionController::class, 'viewDocumentIdFront'])
            ->middleware('can:isMySubscription,subscription')
            ->name('subscription.payment-exemption.viewDocumentIdFront');

        Route::get('subscription/{subscription}/payment-exemption/view-id-back', [PaymentExemptionController::class, 'viewDocumentIdBack'])
            ->middleware('can:isMySubscription,subscription')
            ->name('subscription.payment-exemption.viewDocumentIdBack');

        Route::get('subscription/{subscription}/payment-exemption/view-form', [PaymentExemptionController::class, 'viewDocumentForm'])
            ->middleware('can:isMySubscription,subscription')
            ->name('subscription.payment-exemption.viewDocumentForm');

        Route::get('subscription/{subscription}/payment/', [PagTesouroController::class, 'index'])
            ->middleware(['can:isMySubscription,subscription', 'can:paymentAvailable,subscription.notice'])
            ->name('subscription.payment');

        Route::post('subscription/{subscription}/payment/', [PagTesouroController::class, 'store'])
            ->middleware(['can:isMySubscription,subscription', 'can:paymentAvailable,subscription.notice'])
            ->name('subscription.payment.store');

        Route::get('subscription/{subscription}/payment/{paymentRequest}', [PagTesouroController::class, 'show'])
            ->middleware(['can:isMySubscription,subscription', 'can:paymentAvailable,subscription.notice'])
            ->name('subscription.payment.show');

        Route::get('subscription/{subscription}/payment/{paymentRequest}/payment-status', [PagTesouroController::class, 'viewPaymentStatus'])
            ->middleware(['can:isMySubscription,subscription', 'can:paymentAvailable,subscription.notice'])
            ->name('subscription.payment.payment-status');
    });


Route::middleware(['auth:sanctum', 'verified', 'can:isAdmin'])
    ->prefix('dev')
    ->name('dev.')
    ->group(function () {
        Route::get('/single', function () {
            return view('the-single');
        })->name('single');
        Route::get('/single_table', function () {
            return view('the-single-table');
        })->name('table');
        Route::get('/single_form', function () {
            return view('the-single-form');
        })->name('form');
        Route::get('/single_cards', function () {
            return view('the-single-cards');
        })->name('single-cards');
        Route::resource('maintenance', MaintenanceController::class);
    });
