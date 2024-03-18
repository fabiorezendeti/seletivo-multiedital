<?php

namespace App\Http\Controllers\Candidate;

use App\Models\Process\Notice;
use App\Http\Controllers\Controller;
use App\Models\Process\Subscription;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{

    public function show(Notice $notice)
    {                
        $subscription = Auth::user()->subscriptions()
            ->with('distributionOfVacancy')
            ->where('notice_id',$notice->id)->first() ?? new Subscription();
        $offers = $notice->offers()->with(['courseCampusOffer' => function ($query) {
            $query->with(['campus', 'course', 'shift']);            
        }])
            ->whereHas('distributionVacancies')
            ->get();
        return view('candidate.notice', compact('notice', 'offers','subscription')); 
    }

}
