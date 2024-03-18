<?php

namespace App\Http\Controllers;

use App\Models\Process\Notice;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $subscriptions = $user->subscriptions()
            ->whereDoesntHave('notice',function($q){
                $q->isClosed();
            })
            ->orderBy('id','DESC')
            ->with('distributionOfVacancy')->get();
        $noticesOpened = Notice::subscriptionOpened()->userSubscriptionExcepted($user)->orderBy('id','DESC')->get();
        $noticesInProcess = Notice::subscriptionClosed($closed=true)->userSubscriptionExcepted($user)->orderBy('id','DESC')->get();
        return view('dashboard', compact('noticesOpened', 'noticesInProcess','subscriptions'));
    }

}
