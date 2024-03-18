<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Process\Subscription;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $counters = array(
            'totalSubscriptions' => self::totalSubscriptions(),
            'totalNotices' => self::totalNotices(),
            'totalVacancies' => self::totalVacancies()
        );
        $charts  = array(
            'chartNoticesByModality' => self::chartNoticesByModality(),
            'chartSubscriptionsByModality' => self::chartSubscriptionsByModality(),
            'chartSubscriptionsByAffirmativeAction' => self::chartSubscriptionsByAffirmativeAction(),
            'chartTotalCandidatesByCities' => self::chartTotalCandidatesByCities()
        );

        $calendar = self::calendarNextEvents();
        
        return view('admin.dashboard-admin.index', compact('counters','charts','calendar'));
    }

    public function totalSubscriptions()
    {
        return Subscription::where('is_homologated', true)->count();
    }

    public function totalNotices()
    {
        return Notice::count();
    }

    public function totalVacancies()
    {
        return Offer::sum('total_vacancies');
    }

    public function chartNoticesByModality(){
        $chart = DB::table('notices')->select('modalities.slug',DB::raw('count (*) as notices'))
        ->join('modalities', 'notices.modality_id','=','modalities.id')->groupBy('modalities.slug')->get();
        return $chart;
    }
    public function chartSubscriptionsByModality(){
        $chart = DB::table('subscriptions')->select('modalities.slug',DB::raw('count (*) as subscriptions'))
        ->join('notices', 'notices.id', '=', 'subscriptions.notice_id')
        ->join('modalities', 'notices.modality_id','=','modalities.id')
        ->where('subscriptions.is_homologated', '=', 'true')
        ->groupBy('modalities.slug')->get();
        return $chart;
    }

    public function chartSubscriptionsByAffirmativeAction(){
        $chart = DB::table('subscriptions')->select('affirmative_actions.slug',DB::raw('count (*) as subscriptions'))
        ->join('distribution_of_vacancies', 'subscriptions.distribution_of_vacancies_id','=','distribution_of_vacancies.id')
        ->join ('affirmative_actions', 'distribution_of_vacancies.affirmative_action_id','=','affirmative_actions.id')
        ->where('subscriptions.is_homologated', '=', 'true')
        //->where('affirmative_actions.is_wide_competition','=','false')
        ->groupBy('affirmative_actions.slug')->get();
        foreach ($chart as $row) {
            $row->slug = strtoupper($row->slug);
            $row->slug = str_replace("*","",$row->slug);
        }
        //agrupa ações afirmativas com nomes iguais
        $newChart = [];
        foreach($chart as $a) {
            if (!isset($newChart[$a->slug])) {
                $newChart[$a->slug] = $a;
            } else {
                if ($newChart[$a->slug]->slug == $a->slug) {
                $newChart[$a->slug]->subscriptions = strval($a->subscriptions + $newChart[$a->slug]->subscriptions); 
                } 
            }
        }

        return $newChart;
    }

    public function calendarNextEvents(){
        $firstDay = Carbon::now()->startOfMonth();
        $lastDay = Carbon::now()->endOfMonth();

        $subscriptionInitialDates = DB::table('notices')->select('number',DB::raw("'Inicia inscrições' as label"),'subscription_initial_date as date')
        ->whereBetween('subscription_initial_date',[$firstDay,$lastDay])
        ->orderBy('subscription_initial_date')->get();
        $subscriptionFinalDates = DB::table('notices')->select('number',DB::raw("'Encerra inscrições' as label"),'subscription_final_date as date')
        ->whereBetween('subscription_final_date',[$firstDay,$lastDay])->get();

        $classificationReviewInitialDate = DB::table('notices')->select('number',DB::raw("'Inicia recursos' as label"),'classification_review_initial_date as date')
        ->whereBetween('classification_review_initial_date',[$firstDay,$lastDay])->get();
        $classificationReviewFinalDate = DB::table('notices')->select('number',DB::raw("'Encerra recursos' as label"),'classification_review_final_date as date')
        ->whereBetween('classification_review_final_date',[$firstDay,$lastDay])->get();
        
        $dates = array(
            'subscriptionInitialDates' => $subscriptionInitialDates,
            'subscriptionFinalDates' => $subscriptionFinalDates,
            'classificationReviewInitialDate' => $classificationReviewInitialDate,
            'classificationReviewFinalDate' => $classificationReviewFinalDate
        );
        if(
            (count($dates['subscriptionInitialDates'])+count($dates['subscriptionFinalDates'])+count($dates['classificationReviewInitialDate'])+count($dates['classificationReviewFinalDate']))
             == 0) {
                $dates = [];
            }
        return $dates;
    }


    public function chartTotalCandidatesByCities(){
        $chart = DB::table('subscriptions')
            ->select(
                'cities.name as city',
                'states.name as state',
                DB::raw('count(cities.name) as total')
            )
            ->join('users', 'users.id', '=', 'subscriptions.user_id')
            ->join('contacts', 'contacts.user_id', '=', 'subscriptions.user_id')
            ->join('cities', 'cities.id', '=', 'contacts.city_id')
            ->join('states', 'states.id', '=', 'cities.state_id')
            ->join('distribution_of_vacancies', 'distribution_of_vacancies.id', '=', 'subscriptions.distribution_of_vacancies_id')
            ->join('offers', 'offers.id', '=', 'distribution_of_vacancies.offer_id')
            ->join('course_campus_offers', 'course_campus_offers.id', '=', 'offers.course_campus_offer_id')
            ->join('campuses', 'course_campus_offers.campus_id', '=', 'campuses.id')
            ->where('subscriptions.is_homologated', '=', 'true');
            
            $chart = $chart
            ->groupBy(
                'cities.name',
                'states.name',
            )
            ->orderBy('total', 'desc')
            ->limit(5)->get();
            //dd($chart);
            return $chart;
    }
}
