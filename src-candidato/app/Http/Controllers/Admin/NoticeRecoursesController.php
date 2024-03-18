<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process\Notice;
use App\Models\Process\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeRecoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Notice $notice)
    {
        $subscriptions = $notice->subscriptions()->hasPreliminaryClassificationRecourse()->get();
        return view('admin.notices.recourses.index',compact(
            'notice',
            'subscriptions'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {                
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request,Notice $notice, $subscription)
    {                
        $subscription = Subscription::findBySubscriptionNumber($subscription)->firstOrFail(); 
        $this->authorize('allowFeedbackToPreliminaryClassificationRecourse',$subscription);
        $feedback = $subscription->setPreliminaryClassificationRecourseFeedback(
            Auth::user(),
            Carbon::now(),
            $request->feedback['text'],
            $request->feedback['approved']
        );
        $subscription->forceFill(['preliminary_classification_recourse->feedback'=>$feedback]);
        $subscription->save();        
        return response($subscription->preliminary_classification_recourse,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
