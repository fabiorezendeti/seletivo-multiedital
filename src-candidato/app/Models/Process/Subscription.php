<?php

namespace App\Models\Process;

use Carbon\Carbon;
use App\Models\User;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\DB;
use App\Models\Organization\Campus;
use BaconQrCode\Renderer\ImageRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Collection;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    public $fillable = [
        'notice_id',
        'user_id',
        'distribution_of_vacancies_id',
        'is_homologated',
        'special_need_id',
        'special_need_description',
        'additional_test_time',
        'exam_resource_id',
        'exam_resource_description',
        'exam_room_booking_id'
    ];


    protected $appends = ['homologation_status','score','afericao_ppi_status'];

    private $scoreTemp = null;

    protected $casts = [
        'preliminary_classification_recourse'   => 'array',
        'elimination'   => 'array',
        'additional_test_time_analysis' => 'array',
        'exam_resource_analysis'    => 'array'
    ];

    public function scopeFindBySubscriptionNumber($query, $subscriptionNumber)
    {
        $query->where('subscription_number', $subscriptionNumber);
    }

    public function scopeByCampus($query, Campus $campus)
    {
        $query->whereHas('distributionOfVacancy.offer.courseCampusOffer',function($q) use ($campus){
            $q->where('campus_id',$campus->id);
        });
    }

    public function scopeByCampuses($query, Collection $campus)
    {
        $query->whereHas('distributionOfVacancy.offer.courseCampusOffer',function($q) use ($campus){
            $q->whereIn('campus_id',$campus->pluck('id'));
        });
    }


    public function scopeByCampusesByIds($query, $campus)
    {
        $query->whereHas('distributionOfVacancy.offer.courseCampusOffer',function($q) use ($campus){
            $q->whereIn('campus_id',$campus);
        });
    }

    public function distributionOfVacancy()
    {
        return $this->belongsTo('App\Models\Process\DistributionOfVacancies', 'distribution_of_vacancies_id');
    }

    public function examRoomBooking()
    {
        return $this->belongsTo('App\Models\Process\ExamRoomBooking','exam_room_booking_id');
    }

    public function freezes()
    {
        return $this->hasMany('App\Models\Process\SubscriptionFreeze');
    }

    /**
     * Apenas para score informado durante o processo de inscrição, no caso de inscrição customizada
     */
    public function getScore()
    {
        if(!$this->id) return null;
        if ($this->distributionOfVacancy->selection_criteria_id < 3) return null;
        try {
            $table = $this->notice->getScoreTableNameForCriteriaId($this->distributionOfVacancy->selection_criteria_id);
            if ($table && !$this->scoreTemp) {
                $this->scoreTemp = DB::table($table)->where('subscription_id',$this->id)->first();
            }
            return $this->scoreTemp;
        } catch (QueryException $query) {
            return null;
        }
    }

    public function getScoreAttribute()
    {
        return $this->getScore();
    }

    public function getHomologationStatusAttribute() : string
    {
        $status = [
            null => 'Inscrito',
            1   => 'Homologado',
            0   => 'Não Homologado',
        ];
        return $status[$this->is_homologated];
    }

    public function scopeIsHomologated($query)
    {
        $query->where('is_homologated',true);
    }

    public function scopeIsEliminated($query)
    {
        $query->whereNotNull('elimination');
    }

    public function scopeIsNotEliminated($query)
    {
        $query->whereNull('elimination');
    }

    public function hasSupportingDocuments()
    {
        $doc = $this->getScore()->documento_comprovacao ?? false;
        return ($doc) ? true : false;
    }

    public function getQrCode()
    {
        $renderer = new ImageRenderer(new RendererStyle(150), new SvgImageBackEnd());
        $writer = new Writer($renderer);
        return $writer->writeString("{$this->getSubscriptionNumber()}");
    }

    public function getQrCodeVacancyCertificate(string $url)
    {
        $renderer = new ImageRenderer(new RendererStyle(150), new SvgImageBackEnd());
        $writer = new Writer($renderer);
        return $writer->writeString($url);
    }

    public function setPreliminaryClassificationRecourse(Carbon $date, int $position, string $justify): void
    {
        $this->preliminary_classification_recourse = [
            'date'  => $date,
            'date_ptBR' => $date->format('d/m/Y H:i'),
            'position' => $position,
            'justify'   => $justify,
        ];
    }

    public function setElimination(Carbon $date, string $reason, User $user): void
    {
        $this->elimination = [
            'date'  => $date,
            'date_ptBR' => $date->format('d/m/Y H:i'),
            'reason' => $reason,
            'user'  => [
                'uuid'  => $user->uuid,
                'name'  => $user->name,
                'cpf'   => $user->cpf
            ]
        ];
    }

    public function getPreliminaryClassificationRecourseFeedback()
    {
        return $this->preliminary_classification_recourse['feedback'] ?? null;
    }

    public function getAdditionalTestTimeAnalysis()
    {
        return $this->additional_test_time_analysis['feedback'] ?? null;
    }

    public function getExamResourceAnalysis()
    {
        return $this->exam_resource_analysis['feedback'] ?? null;
    }


    public function checkIfAdditionalTestTimeAnalysisIsApproved()
    {
        if ($this->getAdditionalTestTimeAnalysis())
            return  (bool) $this->additional_test_time_analysis['approved'];
        return null;
    }

    public function checkIfExamResourceAnalysisIsApproved()
    {
        if ($this->getExamResourceAnalysis())
            return  (bool) $this->exam_resource_analysis['approved'];
        return null;
    }

    public function checkIfPreliminaryClassificationRecourseIsApproved()
    {
        if ($this->getPreliminaryClassificationRecourseFeedback())
            return  (bool) $this->preliminary_classification_recourse['feedback']['approved'];
        return null;
    }

    public function setPreliminaryClassificationRecourseFeedback(User $user, Carbon $date, string $feedback, bool $approved)
    {
        return [
            'date'  => $date,
            'date_ptBR' => $date->format('d/m/Y H:i'),
            'feedback'  => $feedback,
            'approved'  => $approved,
            'approved_ptBR'  => ($approved === true) ? 'Sim' : 'Não',
            'user'  => [
                'uuid'  => $user->uuid,
                'name'  => $user->name,
                'cpf'   => $user->cpf
            ]
        ];
    }

    public function getAdditionalTestTimeAnalysisTemplate()
    {
        $date = Carbon::now();
        return [
            'date'  => $date,
            'date_ptBR' => $date->format('d/m/Y H:i'),
            'feedback'  => null,
            'approved'  => null,
            'approved_ptBR'  => null,
            'user'  => [
                'uuid'  => null,
                'name'  => null,
                'cpf'   => null
            ]
        ];
    }

    public function getExamResourceAnalysisTemplate()
    {
        $date = Carbon::now();
        return [
            'date'  => $date,
            'date_ptBR' => $date->format('d/m/Y H:i'),
            'feedback'  => null,
            'approved'  => null,
            'approved_ptBR'  => null,
            'user'  => [
                'uuid'  => null,
                'name'  => null,
                'cpf'   => null
            ]
        ];
    }

    public function scopeHasPreliminaryClassificationRecourse($query)
    {
        return $query->whereNotNull('preliminary_classification_recourse');
    }

    public function scopeHasAdditionalTestTimeRequest($query)
    {
       return $query->where('additional_test_time',true);
    }

    public function scopeHasExamResourceRequest($query)
    {
       return $query->whereNotNull('exam_resource_id');
    }

    public function getSubscriptionNumber()
    {
        return $this->subscription_number ?? $this->id;
    }

    public function makeSubscriptionNumber(Notice $notice, User $user)
    {
        $noticePart = substr($notice->number, -2);
        if (!is_int($noticePart)) $noticePart = Carbon::now()->format('y');
        $userPart = (int) substr($user->cpf, 5, 2);
        if (!is_int($userPart)) $userPart = random_int(10, 99);
        if ($userPart < 10) $userPart = '0' . $userPart;
        $subscriptionPart = $this->id;
        return $noticePart . $userPart . $subscriptionPart;
    }

    public function notice()
    {
        return $this->belongsTo('App\Models\Process\Notice');
    }

    public function specialNeed()
    {
        return $this->belongsTo('App\Models\Process\SpecialNeed','special_need_id');
    }

    public function examResource()
    {
        return $this->belongsTo('App\Models\Process\ExamResource','exam_resource_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getAfericaoPpiStatusAttribute() : string
    {
        if($this->is_ppi_checked){
            return 'Deferido';
        }
        return 'Indeferido';
    }

    public function getModalitiesForCurriculumAnalisysByTitle($value)
    {
        return $this->notice->getModalitiesForCurriculumAnalisys()->where('title', $value)->first() ?? null;
    }

    public function checkIfScoreHasOnlyAverageByModality(string $modality = null)
    {
        $modal = $this->getModalitiesForCurriculumAnalisysByTitle($modality);
        if(!$modal) return false;
        return $modal->title === 'Ensino Médio Regular';
    }

    public function paymentExemption()
    {
        return $this->belongsTo('App\Models\Process\PaymentExemption', 'payment_exemption_id');
    }

    public function hasPaymentExemptionDocuments()
    {
        return $this->payment_exemption_id != null ? true : false;
    }

    public function hasExamRoomBooking()
    {
        return $this->exam_room_booking_id ?? false;
    }

    public function answerCard()
    {
        return $this->belongsTo('App\Models\Process\AnswerCard', 'subscription_id');
    }

    /**
     * Verifica se houve o aceite dos termos de aceite, exceto o termo de nivelamento de linguas estrangeiras
     * @return bool
     */
    public function acceptedTerms(){
        return $this->term_of_authorization_image_use
            && $this->term_of_responsibility_for_damage_caused
            && $this->term_consent_of_regulation_student_conduct
            && $this->term_of_authorization_for_tours_and_trips
            && $this->term_of_veracity_of_information_provided;
    }

    /**
     * Retorna se a média do aluno foi verificada
     * @return bool
     */
    public function averageWasVerified() : bool
    {
        $table = $this->notice->getScoreTableNameForCriteriaId($this->distributionOfVacancy->selection_criteria_id);
        try{
            $result = DB::table($table)
                ->select('media_verificada')
                ->where('subscription_id', $this->id)
                ->first();
        }catch (QueryException $e){
            return false;
        }
        return $result->media_verificada == true;
    }


}
