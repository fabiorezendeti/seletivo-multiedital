<?php

namespace App\Http\Controllers\Admin;

use App\Models\Process\Notice;
use App\Http\Controllers\Controller;
use App\Models\Organization\Campus;
use App\Models\Process\Offer;
use App\Models\Process\SelectionCriteria;
use App\Models\Process\Subscription;
use App\Repository\CampusRepository;
use App\Repository\EnrollmentCallRepository;
use App\Services\CsvLib\Interfaces\CsvWriter;
use App\Services\Notice\EnrollmentCallService;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    private $campusRepository;

    public function __construct(CampusRepository $campusRepository)
    {
        $this->campusRepository = $campusRepository;
    }

    public function candidateContacts(Request $request, Notice $notice, CsvWriter $csvWriter)
    {
        $subscriptions = $notice->subscriptions()
            ->with(['distributionOfVacancy' => function ($q) {
                $q->with(['affirmativeAction', 'offer' => function ($q) {
                    $q->with(['courseCampusOffer' => function ($q) {
                        $q->with(['course', 'campus']);
                    }]);
                }]);
            }])
            ->whereHas('distributionOfVacancy.offer.courseCampusOffer', function ($q) use ($request) {
                $q->where('campus_id', $request->campus);
            })
            ->with(['user' => function ($q) {
                $q->with(['contact' => function ($q) {
                    $q->with(['city' => function ($q) {
                        $q->with('state');
                    }]);
                }]);
            }])
            ->orderBy('distribution_of_vacancies_id');

        $campuses = $this->campusRepository->getCampusesByNotice($notice);

        if ($request->type === 'csv') {
            $subscriptions = $subscriptions->get();
            $csvWriter->insertOne(
                ['Inscrição', 'Nome', 'E-mail', 'Telefone', 'Telefone Alternativo', 'Cidade', 'UF', 'Curso', 'Ação Afirmativa']
            );
            foreach ($subscriptions as $subscription) {
                $csvWriter->insertOne(
                    [
                        'subscription_number'  => $subscription->subscription_number,
                        'name'            => $subscription->user->name,
                        'email'           => $subscription->user->email,
                        'phone_number'    => $subscription->user->contact->phone_number ?? null,
                        'alternative_phone_number' => $subscription->user->contact->alternative_phone_number ?? null,
                        'city'              => $subscription->user->contact->city->name ?? null,
                        'state'             => $subscription->user->contact->city->state->slug ?? null,
                        'course'                  => $subscription->distributionOfVacancy->offer->courseCampusOffer->course->name,
                        'affirmative_action'    => $subscription->distributionOfVacancy->affirmativeAction->slug
                    ]
                );
            }
            return $csvWriter->output("Relatório-de-Candidatos-Com-Contatos.csv");
        }

        if ($request->type === 'html') {
            $campus = Campus::findOrFail($request->campus);
            $subscriptions = $subscriptions->get();
            return view(
                'admin.reports.html.contacts',
                compact('notice', 'subscriptions', 'campus')
            );
        }
        $subscriptions = $subscriptions->paginate();
        return view('admin.reports.contacts', compact(
            'notice',
            'subscriptions',
            'campuses'
        ));
    }

    public function candidatesAddress(Request $request, Notice $notice, EnrollmentCallRepository $enrollmentCallRepository){
        $calls = $enrollmentCallRepository->callsByNotice($notice);
        if (!$calls) return abort(404, 'Não existem chamadas ainda');
        $campuses = $this->campusRepository->getCampusesByNotice($notice);
        if (!$request->html) {
            return view('admin.reports.candidates-address', compact('notice','calls','campuses'));
        }
        $selection_criterias = $notice->getNoticeSelectionCriterias();
        $campus = Campus::where('id', $request->campus)->first();
        $subscriptions = new Collection();
        foreach ($selection_criterias as $s){
            $query = $enrollmentCallRepository->getApprovedListByNoticeAndCriteriaAndCallNumber($notice, $s, $request->call_number)
                ->join('offers as of', 'of.id', '=', 'call.offer_id')
                ->join('course_campus_offers as ccof', 'ccof.id', '=', 'of.course_campus_offer_id')
                ->where('ccof.campus_id', $campus->id);
            $results = $query->get();
            $subscriptions = $subscriptions->merge($results);
        }
        //ORDENAMENTO DOS DADOS POR NOME DO CURSO E NOME DO CANDIDATO
        $subscriptions = $subscriptions->sortBy(function($subs){
            return $subs->distributionOfVacancy->offer->courseCampusOffer->course->name . $subs->user->name;
        })->all();

        return view('admin.reports.html.candidates-address', compact('notice', 'campus','subscriptions'));
    }

    public function subscriptionsApproveds(Request $request, Notice $notice){

        if (!$request->html) {
            $results = DB::table('core.offers as o')
                ->join('course_campus_offers as cco', 'o.course_campus_offer_id', '=', 'cco.id')
                ->join('campuses as c', 'cco.campus_id', '=', 'c.id')
                ->join('courses as c2', 'cco.course_id', '=', 'c2.id')
                ->where('o.notice_id', '=', $notice->id)
                ->select('o.id', 'c.name as campus', 'c2.name as curso')
                ->orderBy('c.name')
                ->orderBy('c2.name')
                ->get();


            return view('admin.reports.subscriptions-approveds', compact(
                'notice',
                'results'
            ));
        }

        $results = DB::table('core.subscriptions as s')
            ->join('core.notices as n', 's.notice_id','=','n.id')
            ->join('core.users as u', 's.user_id','=','u.id')
            ->join('core.distribution_of_vacancies as dov', 's.distribution_of_vacancies_id','=','dov.id')
            ->join('core.offers as o', 'dov.offer_id','=','o.id')
            ->join('core.course_campus_offers as cco', 'o.course_campus_offer_id','=','cco.id')
            ->join('core.campuses as c', 'cco.campus_id','=','c.id')
            ->join('core.courses as c2', 'cco.course_id','=','c2.id')
            ->select('s.subscription_number as inscricao', 'u.name as nome', 'c2.name as curso', 'c.name as campus', 's.is_homologated as homologado')
            ->where('s.notice_id','=',$notice->id)
            ->where('s.is_homologated','=',true);

        if($request->offer)
            $results = $results->where('o.id', '=', $request->offer);

        $results = $results->orderBy('c.name')
                            ->orderBy('c2.name')
                            ->orderBy('u.name')
                            ->get();

        return view('admin.reports.html.subscriptions-approveds', compact(
            'notice',
            'results'
        ));

    }

    public function totalSubscriptions(Request $request, Notice $notice)
    {
        if (!$request->html) {
            $selectionCriterias = $notice->selectionCriterias()->get();
            $campuses = $this->campusRepository->getCampusesByNotice($notice);
            return view('admin.reports.total', compact('notice', 'campuses', 'selectionCriterias'));
        }
        $campuses = ($request->campus)
            ? Campus::withVacanciesByNotice($notice)->where('id', $request->campus)->get()
            : $this->campusRepository->getCampusesByNotice($notice);
        $selectionCriteria = SelectionCriteria::find($request->criteria);
        return view('admin.reports.html.total', compact('notice', 'campuses', 'selectionCriteria'));
    }

    public function totalBySelectionCriterias(Request $request, Notice $notice)
    {
        if (!$request->html) {
            $campuses = $this->campusRepository->getCampusesByNotice($notice);
            return view('admin.reports.total-by-selection-criterias', compact('notice', 'campuses'));
        }

        $campuses = ($request->campus)
            ? Campus::withVacanciesByNotice($notice)->where('id', $request->campus)->get()
            : $this->campusRepository->getCampusesByNotice($notice);
        $selectionCriteriaList = $notice->getNoticeSelectionCriterias();

        return view('admin.reports.html.total-by-selection-criteria', compact(
            'notice',
            'campuses',
            'selectionCriteriaList'
        ));
    }

    public function totalByAffirmativeActions(Request $request, Notice $notice)
    {
        $campuses = ($request->campus)
            ? Campus::withVacanciesByNotice($notice)->where('id', $request->campus)->get()
            : $this->campusRepository->getCampusesByNotice($notice);
        $affirmativeActionList = $notice->getNoticeAffirmativeActions();
        $selectionCriteria = SelectionCriteria::find($request->criteria);
        return view('admin.reports.html.total-by-affirmative-action', compact(
            'notice',
            'campuses',
            'affirmativeActionList',
            'selectionCriteria'
        ));
    }

    public function candidatesForVacancies(Request $request, Notice $notice)
    {
        if (!$request->html) {
            $selectionCriterias = $notice->selectionCriterias()->get();
            $campuses = $this->campusRepository->getCampusesByNotice($notice);
            return view('admin.reports.candidate-by-vacancies', compact('notice', 'campuses', 'selectionCriterias'));
        }
        $campuses = ($request->campus)
            ? Campus::withVacanciesByNotice($notice)->where('id', $request->campus)->get()
            : $this->campusRepository->getCampusesByNotice($notice);
        $selectionCriteria = SelectionCriteria::find($request->criteria);

        return view('admin.reports.html.candidate-by-vacancies', compact('notice', 'campuses', 'selectionCriteria'));
    }

    public function totalCandidatesByCities(Request $request, Notice $notice)
    {
        if (!$request->html) {
            $campuses = $this->campusRepository->getCampusesByNotice($notice);
            return view(
                'admin.reports.total-candidates-by-cities',
                compact('notice', 'campuses')
            );
        }

        $resultSet = DB::table('subscriptions')
            ->select(
                DB::raw('count(cities.name) as total'),
                'cities.name as city',
                'states.name as state',
            )
            ->join('users', 'users.id', '=', 'subscriptions.user_id')
            ->join('contacts', 'contacts.user_id', '=', 'subscriptions.user_id')
            ->join('cities', 'cities.id', '=', 'contacts.city_id')
            ->join('states', 'states.id', '=', 'cities.state_id')
            ->join('distribution_of_vacancies', 'distribution_of_vacancies.id', '=', 'subscriptions.distribution_of_vacancies_id')
            ->join('offers', 'offers.id', '=', 'distribution_of_vacancies.offer_id')
            ->join('course_campus_offers', 'course_campus_offers.id', '=', 'offers.course_campus_offer_id')
            ->join('campuses', 'course_campus_offers.campus_id', '=', 'campuses.id')
            ->where('subscriptions.notice_id', $notice->id);

        if ($request->campus) {
            $resultSet = $resultSet->where([
                ['offers.notice_id', '=', $notice->id], ['campuses.id', '=', $request->campus]
            ]);
        } else {
            $resultSet = $resultSet->where('offers.notice_id', '=', $notice->id);
        }


        $resultSet = $resultSet
            ->groupBy(
                'cities.name',
                'states.name',
            )
            ->orderBy('total', 'desc')
            ->get();

        $campus = Campus::find($request->campus);
        return view(
            'admin.reports.html.total-candidates-by-cities',
            compact('notice', 'resultSet', 'campus')
        );
    }

    public function candidateAffirmativeActionsPPI(Request $request, Notice $notice, EnrollmentCallRepository $enrollmentCallRepository)
    {
        $subscriptions = $notice->getConvokePPI($request->campus);


        $campuses = $this->campusRepository->getCampusesByNotice($notice);

        if ($request->contacts)
        {
            $campus = Campus::findOrFail($request->campus);
            $subscriptions = $subscriptions->get();
            return view(
                'admin.reports.html.affirmative-actions-ppi-with-contacts',
                compact('notice', 'subscriptions', 'campus')
            );
        }

        if ($request->html) {
            $campus = Campus::findOrFail($request->campus);
            $subscriptions = $subscriptions->get();
            return view(
                'admin.reports.html.affirmative-actions-ppi',
                compact('notice', 'subscriptions', 'campus')
            );
        }

        $subscriptions = $subscriptions->paginate();
        return view('admin.reports.affirmative-actions-ppi', compact(
            'notice',
            'subscriptions',
            'campuses'
        ));
    }

    public function preliminaryClassificationRecourse(Request $request, Notice $notice)
    {
        $subscriptions = $notice->subscriptions()
            ->with(['distributionOfVacancy' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->with(['courseCampusOffer' => function ($q) {
                        $q->with(['course', 'campus']);
                    }]);
                }]);
            }])
            ->whereHas('distributionOfVacancy.offer.courseCampusOffer', function ($q) use ($request) {
                $q->where('campus_id', $request->campus);
            })
            ->whereNotNull('preliminary_classification_recourse');

        $campuses = $this->campusRepository->getCampusesByNotice($notice);

        if ($request->html) {
            $campus = Campus::findOrFail($request->campus);
            $subscriptions = $subscriptions->get();
            return view(
                'admin.reports.html.preliminary-classification-recourse',
                compact('notice', 'subscriptions', 'campus')
            );
        }
        $subscriptions = $subscriptions->paginate();
        return view(
            'admin.reports.preliminary-classification-recourse',
            compact(
                'notice',
                'subscriptions',
                'campuses'
            )
        );
    }

    public function checkPPI(Request $request, Notice $notice)
    {
        $subscriptions = $notice->getConvokePPI($request->campus);

        $campuses = $this->campusRepository->getCampusesByNotice($notice);

        if ($request->html) {
            $campus = Campus::findOrFail($request->campus);
            $subscriptions = $subscriptions->get();
            return view(
                'admin.reports.html.check-ppi',
                compact('notice', 'subscriptions', 'campus')
            );
        }
        $subscriptions = $subscriptions->paginate();
        return view('admin.reports.check-ppi', compact(
            'notice',
            'subscriptions',
            'campuses'
        ));
    }

    public function distributedLotteryNumber(Request $request, Notice $notice)
    {
        try {
            if ($request->offer) {
                $offer = Offer::findOrFail($request->offer);
                $subscriptions =
                    Subscription::isHomologated()
                    ->select(
                        'subscriptions.subscription_number',
                        'subscriptions.user_id',
                        'lottery.lottery_number'
                    )
                    ->with('user')
                    ->whereHas('distributionOfVacancy', function ($q) use ($offer) {
                        $q->where('offer_id', $offer->id);
                    })
                    ->join($notice->getLotteryTable() . ' as lottery', 'lottery.subscription_id', '=', 'subscriptions.id')
                    ->orderBy("lottery.lottery_number")
                    ->get();


                return view(
                    'admin.reports.html.distributed-lottery-number',
                    compact('notice', 'subscriptions', 'offer')
                );
            }

            $offers = $notice->offers()->whereHas('distributionVacancies', function ($q) {
                $q->whereLottery();
            })->get();

            return view('admin.reports.distributed-lottery-number', compact(
                'notice',
                'offers'
            ));
        } catch (QueryException $exception) {
            return abort(404, 'O sorteio ainda não foi realizado');
        }
    }

    public function registeredCandidates(Request $request, Notice $notice)
    {
        $offers = ($request->oferta)
            ? $notice->offers()->where('id', $request->oferta)->get()
            : $notice->offers()->get();

        $status = ($request->status);
        //?

        if ($request->html) {
            $enrollmentCallRepository = new EnrollmentCallRepository();
            $selectionCriteria = SelectionCriteria::findOrFail($request->criteria);
            return view('admin.reports.html.registered-candidates', compact(
                'notice',
                'offers',
                'status',
                'selectionCriteria',
                'enrollmentCallRepository'
            ));
        }

        $selectionCriterias = $notice->selectionCriterias()->get();
        $campuses = $this->campusRepository->getCampusesByNotice($notice);
        return view('admin.reports.registered-candidates', compact('notice', 'campuses', 'offers', 'selectionCriterias'));
    }

    public function candidatesWithScores(Request $request, Notice $notice)
    {
        $offers = ($request->oferta)
            ? $notice->offers()->where('id', $request->oferta)->get()
            : $notice->offers()->get();

        $status = ($request->status);
        //?

        if ($request->html) {
            $selectionCriteria = SelectionCriteria::findOrFail($request->criteria);
            $modality = $notice->getModalitiesForCurriculumAnalisys()->where('title', $request->modality ?? null);
            $offer = Offer::findOrFail($request->offer);
            $subscriptions = $notice->getScoreClassificationByCriteria($selectionCriteria, $offer, 'user_name');
            if ($request->modality) {
                $subscriptions = $subscriptions->where('modalidade', $request->modality);
            }
            return view('admin.reports.html.candidates-with-scores', compact(
                'notice',
                'offer',
                'selectionCriteria',
                'subscriptions'
            ));
        }

        $selectionCriterias = $notice->selectionCriterias()->get();
        $campuses = $this->campusRepository->getCampusesByNotice($notice);
        return view('admin.reports.candidates-with-scores', compact('notice', 'campuses', 'offers', 'selectionCriterias'));
    }

    public function summary(Notice $notice, EnrollmentCallRepository $enrollmentCallRepository)
    {
        $ppiCount = $notice->getConvokePPI()->get();
        $totalCandidatesPerCall = $enrollmentCallRepository->totalCandidatesPerCall($notice);
        return view('admin.reports.html.summary', compact(
            'notice',
            'ppiCount',
            'totalCandidatesPerCall'
        ));
    }

    public function totalByCalls(Request $request, Notice $notice, EnrollmentCallRepository $enrollmentCallRepository)
    {
        $calls = $enrollmentCallRepository->callsByNotice($notice);
        if (!$calls) return abort(404, 'Não existem chamadas ainda');
        if (!$request->html) {
            return view('admin.reports.total-by-call', compact('notice','calls'));
        }
        $type = ($request->type === 'matriculados') ? 'matriculado' : 'não matriculado';
        $callNumber = $request->call_number ?? null;
        $callList = collect($enrollmentCallRepository->getTotalByAffirmativeActionWhereStatusAndCallNumber($notice, $type, $callNumber));
        $callList = $callList->mapWithKeys(function($item) {
            return [ $item->offer_id . '_' . $item->affirmative_action_id =>  $item->total ] ;
        });
        $affirmativeActionList = $notice->getNoticeAffirmativeActions()->sortBy('slug');

        return view('admin.reports.html.total-by-call', compact(
            'notice',
            'affirmativeActionList',
            'type',
            'callNumber',
            'callList'
        ));
    }

    public function foreigns(Request $request, Notice $notice, CsvWriter $csvWriter)
    {
        $campuses = $this->campusRepository->getCampusesByNotice($notice);
        $subscriptions = $notice->subscriptions()
            ->with(['distributionOfVacancy' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->with(['courseCampusOffer' => function ($q) {
                        $q->with(['course', 'campus']);
                    }]);
                }]);
            }])
            ->join('users', 'users.id', '=', 'subscriptions.user_id')->where('users.is_foreign',true);

        if ($request->campus) {
            $subscriptions = $subscriptions->whereHas('distributionOfVacancy.offer.courseCampusOffer',
                                        function ($q) use ($request) {
                                            $q->where('campus_id', $request->campus);
                                        });
        }
        if ($request->type === 'html') {
            $campus = Campus::find($request->campus);
            $subscriptions = $subscriptions->get();
            return view(
                'admin.reports.html.foreigns',
                compact('notice', 'subscriptions', 'campus')
            );
        }
        $subscriptions = $subscriptions->paginate();
        return view('admin.reports.foreigns', compact(
            'notice',
            'subscriptions',
            'campuses'
        ));
    }

    public function candidatesWithSocialNome(Request $request, Notice $notice)
    {
        $campuses = $this->campusRepository->getCampusesByNotice($notice);
        $subscriptions = $notice->subscriptions()
            ->with(['distributionOfVacancy' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->with(['courseCampusOffer' => function ($q) {
                        $q->with(['course', 'campus']);
                    }]);
                }]);
            }])
            ->join('users', 'users.id', '=', 'subscriptions.user_id')->whereNotNull('users.social_name');

        if ($request->campus) {
            $subscriptions = $subscriptions->whereHas('distributionOfVacancy.offer.courseCampusOffer',
                                        function ($q) use ($request) {
                                            $q->where('campus_id', $request->campus);
                                        });
        }
        if ($request->type === 'html') {
            $campus = Campus::find($request->campus);
            $subscriptions = $subscriptions->get();
            return view(
                'admin.reports.html.candidates-with-social-name',
                compact('notice', 'subscriptions', 'campus')
            );
        }
        $subscriptions = $subscriptions->paginate();
        return view('admin.reports.candidates-with-social-name', compact(
            'notice',
            'subscriptions',
            'campuses'
        ));
    }

    public function pendingPayments(Request $request, Notice $notice)
    {

        $resultSet = DB::table('subscriptions as s')->select(
            's.subscription_number', 'pr.situacao_codigo', 'users.name as user_name',
            'campuses.name as campus_name', 'courses.name as course_name', 'contacts.phone_number', 'users.email', 's.created_at'
        )
            ->leftJoin('payment_requests as pr', 'pr.subscription_id', '=', 's.id')
            ->join('users', 'users.id', '=', 's.user_id')
            ->join('distribution_of_vacancies', 'distribution_of_vacancies.id', '=', 's.distribution_of_vacancies_id')
            ->join('offers', 'offers.id', '=', 'distribution_of_vacancies.offer_id')
            ->join('course_campus_offers', 'course_campus_offers.id', '=', 'offers.course_campus_offer_id')
            ->join('campuses', 'course_campus_offers.campus_id', '=', 'campuses.id')
            ->join('courses', 'course_campus_offers.course_id', '=', 'courses.id')
            ->join('contacts', 'contacts.user_id', '=', 'users.id')
            ->where('s.notice_id', $notice->id)
            ->where('s.is_homologated', null);

        if ($request->search) {
            $resultSet = $resultSet->where('subscriptions.subscription_number', (int)$request->search);
        }

        $resultSet = $resultSet->orderBy('campuses.name', 'asc')
            ->orderBy('courses.name', 'asc')
            ->orderBy('users.name', 'asc');

        if ($request->html) {
            $subscriptions = $resultSet->get();
            return view('admin.reports.html.pending-payments', compact('notice', 'subscriptions'));
        }

        $subscriptions = $resultSet->paginate();
        return view('admin.reports.pending-payments', compact(
            'notice',
            'subscriptions'
        ));
    }

    public function candidatesAged18(Request $request, Notice $notice)
    {
        /**
         * Candidatos com mais de 18 anos de idade
        */

        $resultSet = DB::table('subscriptions')
            ->select(
                'subscriptions.subscription_number', 'users.name as user_name',
                'campuses.name as campus_name', 'courses.name as course_name','users.email', 'users.birth_date'
            )
            ->join('users', 'users.id', '=', 'subscriptions.user_id')
            ->join('distribution_of_vacancies', 'distribution_of_vacancies.id', '=', 'subscriptions.distribution_of_vacancies_id')
            ->join('offers', 'offers.id', '=', 'distribution_of_vacancies.offer_id')
            ->join('course_campus_offers', 'course_campus_offers.id', '=', 'offers.course_campus_offer_id')
            ->join('campuses', 'course_campus_offers.campus_id', '=', 'campuses.id')
            ->join('courses', 'course_campus_offers.course_id', '=', 'courses.id')
            ->where('subscriptions.notice_id', $notice->id)
            ->where('subscriptions.is_homologated', true)
            ->where('users.birth_date','<',date('Y-m-d', strtotime('-18 years')));
            //->where('users.birth_date','<',(current_date - '18 years'::interval);

        if ($request->search) {
            $resultSet = $resultSet->where('subscriptions.subscription_number', (int)$request->search);
        }

        $resultSet = $resultSet->orderBy('campuses.name', 'asc')
            ->orderBy('courses.name', 'asc')
            ->orderBy('users.name', 'asc');

        $subscriptions = $resultSet->get();
        return view('admin.reports.html.candidates-aged-18', compact('notice', 'subscriptions'));
    }

}
