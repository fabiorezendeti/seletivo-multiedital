<?php

namespace App\Policies;

use App\Models\Process\EnrollmentSchedule;
use App\Models\Process\Subscription;
use App\Models\User;
use App\Models\Pagtesouro\PaymentRequest;
use App\Repository\EnrollmentCallRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function isMySubscription(User $user, Subscription $subscription)
    {
        return $user->id === $subscription->user_id;
    }

    public function allowAfericaoPPI(User $user, Subscription $subscription)
    {
        $subscriptionPeriodIsClosed = !$subscription->notice->inSubscriptionsPeriod();
        if (Gate::allows('isAdmin') && $subscriptionPeriodIsClosed) return true;

        return ($user->permissions()
            ->where('campus_id', $subscription->distributionOfVacancy->offer->courseCampusOffer->campus_id)
            ->whereIn('role_id', [2,3])
            ->count() > 0) && $subscriptionPeriodIsClosed;
    }


    public function allowRequestPreliminaryClassificationRecourse(User $user, Subscription $subscription)
    {        
        return $subscription->notice->inReviewRequestPeriod() && !$subscription->preliminary_classification_recourse;
    }

    public function printCallStatus(User $user, Subscription $subscription)
    {
        if ($subscription->is_homologated !== true) return false;
        return $subscription->notice->enrollment_call_table_created;
    }


    public function allowFeedbackToPreliminaryClassificationRecourse(User $user, Subscription $subscription)
    {
        return !isset($subscription->preliminary_classification_recourse['feedback']);
    }

    public function allowFeedbackToAdditionalTestTimeAnalysis(User $user, Subscription $subscription)
    {
        return $subscription->additional_test_time_analysis['approved'] == null;
    }

    public function allowShowInterest(User $user, Subscription $subscription)
    {
        return $subscription->distributionOfVacancy->isSISU() && $subscription->notice->inShowInterestPeriod() && $subscription->is_homologated === null;
    }

    public function allowExamResourceAnalysis(User $user, Subscription $subscription)
    {
        return $subscription->exam_resource_analysis['approved'] == null;
    }

    public function allowRequestPaymentExemption(User $user, Subscription $subscription)
    {
        return $this->isMySubscription($user, $subscription) &&
            $subscription->notice->inPaymentExemptionPeriod() &&
            !$subscription->hasPaymentExemptionDocuments();
    }

    public function viewEnrollmentProcess(User $user, Subscription $subscription)
    {        
        if (!$subscription->notice->enrollment_process_enable) return false;        
        $hasCalls = Gate::check('printCallStatus', $subscription);
        if (!$hasCalls) return false;
        $call = (new EnrollmentCallRepository)->getCallsBySubscription($subscription);
        return $this->isMySubscription($user, $subscription) && $call->count();
    }

    public function viewEnrollmentDocuments(User $user, Subscription $subscription)
    {
        if (Gate::check('isAcademicRegisterOrAdmin')) return true;
        return $this->isMySubscription($user, $subscription);
    }

    public function hasPaymentRequest(User $user, Subscription $subscription)
    {
        return PaymentRequest::where('subscription_id', $subscription->id)->count() > 0;
    }

    public function allowEnrollmentProcess(User $user, Subscription $subscription)
    {
        if (!$subscription->notice->enrollment_process_enable) return false;
        $hasCalls = Gate::check('printCallStatus', $subscription);
        if (!$hasCalls) return false;
        $callsBySubscription = (new EnrollmentCallRepository)->getCallsBySubscription($subscription);          
        $theCall = $callsBySubscription->where('status', 'pendente')->first();        
        $inPeriod = EnrollmentSchedule::where('call_number', $theCall->call_number ?? 0)->enrollmentOpened()->count();
        return $this->isMySubscription($user, $subscription) && $inPeriod && $theCall;
    }
}
