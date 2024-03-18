<?php

namespace App\Models\Process;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Freeze;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Organization\Campus;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Process\CriteriaCustomization\CurriculumAnalisys\Modality;
use Illuminate\Database\QueryException as DatabaseQueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Process\Exam;
use App\Repository\ParametersRepository;


class Notice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'details',
        'description',
        'details',
        'link',
        'subscription_initial_date',
        'subscription_final_date',
        'classification_review_initial_date',
        'classification_review_final_date',
        'registration_fee',
        'payment_date',
        'modality_id',
        'closed_at',
        'gru_config',
        'pagtesouro_activated',
        'enrollment_process_enable',
        'candidate_additional_instructions',
        'display_exam_room_date',
        'exemption_request_final_date',
        'exam_date',
        'exam_time'
    ];

    protected  $dates = [
        'subscription_initial_date',
        'subscription_final_date',
        'classification_review_initial_date',
        'classification_review_final_date',
        'closed_at',
        'payment_date',
        'display_exam_room_date',
        'exemption_request_final_date',
        'exam_date'
    ];

    protected $casts = [
        'gru_config'   => 'array',
    ];


    protected $hidden = [];

    public function getGruConfigAttribute()
    {
        $parameters = new ParametersRepository();

        if ($this->attributes['gru_config']) return (array)json_decode($this->attributes['gru_config']);
        $date = Carbon::now();
        return [
            'codigo_favorecido'   => $parameters->getValueByName('gru_codigo_favorecido'),
            'gestao'              => $parameters->getValueByName('gru_gestao'),
            'codigo_correlacao'   => $parameters->getValueByName('gru_codigo_correlacao'),
            'nome_favorecido'     => $parameters->getValueByName('gru_nome_favorecido'),
            'codigo_recolhimento' => $parameters->getValueByName('gru_codigo_recolhimento'),
            'nome_recolhimento'   => $parameters->getValueByName('gru_nome_recolhimento'),
            'competencia'         => $date->format('m/Y'),
        ];
    }


    public function setRegistrationFeeAttribute($value)
    {
        $this->attributes['registration_fee'] = ($value) ?  str_replace(',', '.', str_replace('.', '', $value)) : null;
    }

    /**
     * Nunca na sua vida utilize esse método ou faça coisas desse tipo sem uma forte moticação,
     * a motivação aqui foi validar o ensalamento automático apenas para um sala em específico,
     * por isso o uso da atribuição dinâmica (criar atributo em tempo de execução)
     */
    public function appendExamLocation(ExamLocation $examLocation)
    {
        $this->examLocation = $examLocation;
        return $this;
    }


    public function hasFee()
    {
        return $this->registration_fee > 0;
    }

    public function inPaymentPeriod()
    {
        return $this->payment_date->gte(Carbon::now()->format('Y-m-d'));
    }

    public function inReviewRequestPeriod()
    {
        if ($this->selectionCriterias->where('id',5)->count() > 0 ) return false;
        $now = Carbon::now();
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;
        return $now->betweenIncluded($this->classification_review_initial_date, $this->classification_review_final_date);
    }

    public function afterReviewRequestPeriod()
    {
        $now = Carbon::now();
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;
        return $now->greaterThan($this->classification_review_final_date);
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Models\Process\Subscription');
    }

    public function  enrollmentSchedule()
    {
        return $this->hasMany('App\Models\Process\EnrollmentSchedule');
    }

    public function selectionCriterias()
    {
        return $this->belongsToMany('App\Models\Process\SelectionCriteria')->withPivot('customization');
    }

    public function getNoticeSchemaName()
    {
        return "notice_{$this->id}";
    }

    /**
     * @todo Refatorar para tirar lógica confusa que exige este comentário
     * Retorna uma tabela se o critério de seleção não for sorteio ou prova
     * Caso informe $onlyCustomized = false retorna todos os critérios de seleção,
     * ficou redundante, mas preferi garantir isso por enquanto, teoricamente não criaria efeitos colaterais tirar o IF
     */
    public function getScoreTableNameForCriteriaId($selection_criteria_id)
    {
        #if ($selection_criteria_id < 2) return null;
        $table = "{$this->getNoticeSchemaName()}.criteria_{$selection_criteria_id}_score";
        return $table;
    }

    public function getEnrollmentCallTableNameByCriteria(SelectionCriteria $selection_criteria)
    {
        return "{$this->getNoticeSchemaName()}.criteria_{$selection_criteria->id}_call";
    }

    public function getEnrollmentProcessTableName()
    {
        return "{$this->getNoticeSchemaName()}.enrollment_process";
    }

    public function getEnrollmentProcessDocumentsTableName()
    {
        return "{$this->getNoticeSchemaName()}.enrollment_process_documents";
    }

    public function getLotteryTable()
    {
        return "{$this->getNoticeSchemaName()}.criteria_1_score";
    }

    public function getLotteryDraw(?Offer $offer, $orderBy = 'lottery_number')
    {
        $table = $this->getLotteryTable();
        $query = DB::table($table)
            ->select(
                "$table.*",
                'core.subscriptions.subscription_number as subscription_number',
                'core.users.name as user_name',
                'core.subscriptions.is_ppi_checked',
                'core.subscriptions.distribution_of_vacancies_id as subscription_distribution_of_vacancy_id',
                'core.affirmative_actions.slug as affirmative_action_slug'
            )
            ->join('core.subscriptions', 'core.subscriptions.id', '=', "$table.subscription_id")
            ->join('core.distribution_of_vacancies', 'core.distribution_of_vacancies.id', '=', "$table.distribution_of_vacancies_id")
            ->join('core.affirmative_actions', 'core.affirmative_actions.id', '=', 'core.distribution_of_vacancies.affirmative_action_id')
            ->join('core.users', 'core.users.id', '=', 'core.subscriptions.user_id')
            ->orderBy($orderBy);
        if ($offer->id) $query->where("$table.offer_id", $offer->id);
        return $query->get();
    }

    public function getScoreClassificationByCriteria(SelectionCriteria $selectionCriteria, ?Offer $offer, $orderBy = 'global_position')
    {
        $table = $this->getScoreTableNameForCriteriaId($selectionCriteria->id);
        $query = DB::table($table)
            ->select(
                "$table.*",
                'core.subscriptions.subscription_number as subscription_number',
                'core.users.name as user_name',
                'core.subscriptions.is_ppi_checked',
                'core.subscriptions.elimination',
                DB::raw("to_char(core.users.birth_date, 'dd/mm/yyyy' ) as birth_date"),
                'core.subscriptions.distribution_of_vacancies_id as subscription_distribution_of_vacancy_id',
                'core.affirmative_actions.slug as affirmative_action_slug'
            )
            ->join('core.subscriptions', 'core.subscriptions.id', '=', "$table.subscription_id")
            ->join('core.distribution_of_vacancies', 'core.distribution_of_vacancies.id', '=', "core.subscriptions.distribution_of_vacancies_id")
            ->join('core.affirmative_actions', 'core.affirmative_actions.id', '=', 'core.distribution_of_vacancies.affirmative_action_id')
            ->join('core.users', 'core.users.id', '=', 'core.subscriptions.user_id')
            ->where('core.subscriptions.is_homologated', '=', true)
            ->orderBy($orderBy);
        if ($offer->id) $query->where("core.distribution_of_vacancies.offer_id", $offer->id);
        return $query->get();
    }

    public function hasEnem()
    {
        return $this->selectionCriterias->where('id', 3)->count() > 0;
    }

    public function hasSISU()
    {
        return $this->selectionCriterias->where('id', 5)->count() > 0;
    }

    public function hasProva()
    {
        return $this->selectionCriterias->where('id', 2)->count() > 0;
    }

    public function hasCurriculum()
    {
        return $this->selectionCriterias->where('id', 4)->count() > 0;
    }


    public function hasSubscriptions()
    {
        return $this->subscriptions->count() ?? 0;
    }

    /* Tem Gabarito*/
    public function hasExam()
    {
        return Exam::where('notice_id',$this->id)->exists();
    }

    public function getNoticeOffersByCampus(Campus $campus)
    {
        return $this->offers()->whereHas(
            'courseCampusOffer',
            function ($q) use ($campus) {
                $q->where('campus_id', $campus->id);
            }
        )->with(['courseCampusOffer.course' => function ($q) {
            $q->orderBy('name');
        }])
            ->get();
    }

    public function getNoticeSelectionCriterias()
    {
        return SelectionCriteria::whereHas(
            'distributionOfVacancies.offer',
            function ($q) {
                $q->where('notice_id', $this->id);
            }
        )->get();
    }

    public function getNoticeAffirmativeActions()
    {
        return AffirmativeAction::whereHas(
            'distributionOfVacancies.offer',
            function ($q) {
                $q->where('notice_id', $this->id);
            }
        )->get();
    }


    public function scopeNeedsCustomization()
    {
        return $this->selectionCriterias->where('is_customizable', true);
    }

    public function scopeUserSubscriptionExcepted(Builder $query, User $user)
    {
        return $query->whereDoesntHave('subscriptions', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    public function inSubscriptionsPeriod()
    {
        if ($this->selectionCriterias->where('id',5)->count() > 0) return false;
        $now = Carbon::now();
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;
        return $now->betweenIncluded($this->subscription_initial_date, $this->subscription_final_date);
    }

    public function inShowInterestPeriod()
    {
        $now = Carbon::now();
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;
        return $now->betweenIncluded($this->subscription_initial_date, $this->subscription_final_date);
    }

    public function scopeIsClosed($query)
    {
        $now = Carbon::now();
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;
        $query->where('closed_at', '<=', $now);
    }

    public function closed()
    {
        if (!$this->closed_at) return false;
        $now = Carbon::now();
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;
        return $now->lessThanOrEqualTo($this->closed_at);
    }

    public function scopeSubscriptionOpened($query)
    {
        $now = Carbon::now();
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;
        return $query
            ->where('subscription_initial_date', '<=', $now)
            ->where('subscription_final_date', '>=', $now)
            ->whereHas('offers')
            ->whereDoesntHave('selectionCriterias',function($q) {
                $q->where('id',5);
            });
    }

    /**
     * Seleciona Inscrições Encerradas
     * $closed TRUE tras apenas as que ainda são visíveis (closed_at)
     * $closed FALSE tras todas
     *
     * Esse método é utilizado pelas seguintes views:
     * - Dashboard do Candidato: Lista editais em andamento na tela inicial
     * ...
     */
    public function scopeSubscriptionClosed($query, $closed = false)
    {
        $now = Carbon::now();
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;
        if (!$closed)  return $query->where('subscription_final_date', '<=', $now);

        return $query->where(function ($q) use ($now) {
            $q->where('subscription_final_date', '<=', $now);
            $q->where('closed_at', '>=', $now);
        });
    }

    public function totalOfSubscriptionsByAffirmativeActions()
    {
        return $this->subscriptions()
            ->isHomologated()
            ->select('aa.*', DB::raw('count(*) as total'))
            ->join('distribution_of_vacancies as dv', 'dv.id', '=', 'subscriptions.distribution_of_vacancies_id')
            ->join('affirmative_actions as aa', 'aa.id', '=', 'dv.affirmative_action_id')
            ->groupBy('aa.id')
            ->get();
    }

    public function getConvokePPI($campusId = null)
    {
        $ppiInCalls = collect();
        $query = $this->subscriptions()
            ->isHomologated()
            ->whereNull('elimination')
            ->with(['distributionOfVacancy' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->with(['courseCampusOffer' => function ($q) {
                        $q->with(['course', 'campus']);
                    }]);
                }])
                    ->with(['affirmativeAction']);
            }])
            ->whereHas('distributionOfVacancy.affirmativeAction', function ($q) {
                $q->where('is_ppi', true);
            })
            ->with('user');

        $campuses = Auth::user()->permissions()
            ->select('permissions.campus_id')
            ->whereIn('role_id', [2,3])
            ->where('user_id', Auth::user()->id)->get();

        if(Gate::allows('isAcademicRegisterOrPPICommitte')) {
            $query->whereHas('distributionOfVacancy.offer.courseCampusOffer', function ($q) use ($campuses) {
                $q->whereIn('campus_id', $campuses->pluck('campus_id'));
            });
        }

        if ($campusId) {
            $query->whereHas('distributionOfVacancy.offer.courseCampusOffer', function ($q) use ($campusId) {
                $q->where('campus_id', $campusId);
            });
        }

        try {
            foreach ($this->selectionCriterias as $selectionCriteria) {
                $table = $this->getEnrollmentCallTableNameByCriteria($selectionCriteria);
                $ppis = DB::table($table)
                    ->select('subscription_id')
                    ->where('is_wide_concurrency', '=', true)
                    ->get();
                $ppiInCalls = $ppiInCalls->merge($ppis->pluck('subscription_id'));
            }

            if ($ppiInCalls->count() > 0) {
                $query->whereNotIn('id', $ppiInCalls);
            }
        } catch (DatabaseQueryException $exception) {
            $ppiInCalls = collect();
        }
        return $query;
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Process\Offer');
    }

    public function getCampusesWithOffers(){
        return Campus::select('campuses.id', 'campuses.name')
                    ->join('course_campus_offers', 'campuses.id', '=', 'course_campus_offers.campus_id')
                    ->join('offers','course_campus_offers.id', '=', 'offers.course_campus_offer_id')
                    ->where('offers.notice_id','=', $this->id)
                    ->distinct()
                    ->orderBy('campuses.name', 'asc')
                    ->get();
    }

    public function modality()
    {
        return $this->belongsTo('App\Models\Course\Modality');
    }

    public function exam()
    {
        return $this->hasOne('App\Models\Process\Exam');
    }

    public function getModalitiesForCurriculumAnalisys()
    {
        return collect([
            '0' => new Modality('Ensino Médio Regular', 'Ensino Médio Regular, Ensino Médio Técnico ou Outro'),
            '2' => new Modality('ENEM', 'Ensino Médio via Certificação do Enem'),
            '3' => new Modality('ENCCEJA', 'Ensino Médio via Certificação do Encceja'),
        ]);
    }

    public function getModalitiesForCurriculumAnalisysKeyByValue($value)
    {
        return $this->getModalitiesForCurriculumAnalisys()->where('title', $value)->keys()[0];
    }

    public function inPaymentExemptionPeriod()
    {
//        $now = Carbon::now();
//        $now->hour = 00;
//        $now->minute = 00;
//        $now->second = 00;
//        return $now->betweenIncluded($this->subscription_initial_date, $this->subscription_final_date->subDays(15));
        return empty($this->exemption_request_final_date) ? false : Carbon::parse($this->exemption_request_final_date)->gte(Carbon::now()->format('Y-m-d'));
    }

    public function hasExamRoomBookings()
    {
        return $this->examRoomBookings->count() > 0;
    }

    public function examRoomBookings(){
        return $this->hasMany('App\Models\Process\ExamRoomBooking');
    }

    /**
     * Retorna o total de inscrições homologadas do edital ou por campus
     * @param $campus
     * @return mixed
     */
    public function getAmountOfSubscriptions($campus = null){
        $amountOfSubscriptions = $this->subscriptions()->ishomologated();
        $amountOfSubscriptions = is_null($campus) ? $amountOfSubscriptions->count()
            : $amountOfSubscriptions->byCampus($campus)->count();
        return $amountOfSubscriptions;
    }

    /**
     * Retorna o total da capacidade de alocação atual do edital ou de um campus específico
     * @param $campus_id
     * @return mixed
     */
    public function getTotalAllocationCapacity($campus = null){
        $examRoomBookingsCapacity = is_null($campus) ? $this->examRoomBookings->where('active', true)->sum('capacity')
            : ExamRoomBooking::join('exam_locations as el', 'el.id', '=', 'exam_location_id')->where([
                'el.campus_id' => $campus->id,
                'notice_id' => $this->id
            ])->where('exam_room_bookings.active', true)->sum('capacity');
        return $examRoomBookingsCapacity;
    }

    /**
     * Retorna quantas vagas ainda faltam para alocar candidatos nas salas de prova do edital ou em um campus específico
     * @param $campus
     * @return mixed
     */
    public function checkAllocationNeed($campus = null){
        //retorna a quantidade de inscritos homologados e a capacidade de ensalamento para cada local do edital
        $result = DB::table('subscriptions as s')
            ->select('cco.campus_id', 'subquery.exam_location_id', DB::raw('COUNT(s.id) as enrolled'), DB::raw('MAX(subquery.capacity) as capacity'))
            ->join('distribution_of_vacancies as dv', 'dv.id', '=', 's.distribution_of_vacancies_id')
            ->join('offers as o', 'o.id', '=', 'dv.offer_id')
            ->join('course_campus_offers as cco', 'cco.id', '=', 'o.course_campus_offer_id')
            ->join('exam_locations as el', 'el.campus_id', '=', 'cco.campus_id')
            ->join(DB::raw("(SELECT erb.exam_location_id, SUM(erb.capacity) as capacity
               FROM exam_room_bookings as erb
               WHERE erb.notice_id = {$this->id} and erb.active = true
               GROUP BY erb.exam_location_id) as subquery"), 'subquery.exam_location_id', '=', 'el.id')
            ->where('s.is_homologated', true)
            ->where('s.notice_id', $this->id);
        if(!is_null($campus))
            $result->where('el.campus_id', '=', $campus->id);
        $result->groupBy('cco.campus_id', 'subquery.exam_location_id')
            ->havingRaw('AVG(subquery.capacity) < COUNT(s.id)')
            ->orderBy('cco.campus_id');
        $array = $result->get()->toArray();
        $amountOfSubscriptions = array_reduce($array, function ($carry, $element) {
            return $carry + $element->enrolled;
        }, 0);
        $totalAllocationCapacity = array_reduce($array, function ($carry, $element) {
            return $carry + $element->capacity;
        }, 0);
        $diference = $amountOfSubscriptions - $totalAllocationCapacity;
        if($this->getAmountOfSubscriptions($campus) == 0 || $this->getTotalAllocationCapacity($campus) == 0){
            return null;
        }
        return $amountOfSubscriptions > 0 && $totalAllocationCapacity > 0 && $diference > 0 ? $diference : 0;
    }

    public function examIsNotPast(){
        $examDate = Carbon::createFromFormat('Y-m-d H:i:s', $this->exam_date);
        if ($examDate->isPast()) {
            return false;
        }
        return true;
    }

    /**
     * Retorna se todas as inscrições existentes são homologadas
     * @return bool
     */
    public function allSubscriptionsHomologated(): bool
    {
        return $this->subscriptions()->count() == $this->subscriptions()->isHomologated()->count();
    }
}
