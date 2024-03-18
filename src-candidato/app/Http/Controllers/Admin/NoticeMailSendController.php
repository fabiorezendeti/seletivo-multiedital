<?php

namespace App\Http\Controllers\Admin;

use App\Mail\CustomizedMail;
use Illuminate\Http\Request;
use App\Models\Process\Notice;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\BatchMailSend;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Repository\EnrollmentCallRepository;
use App\Repository\ParametersRepository;

class NoticeMailSendController extends Controller
{

    public function mailEditor(Notice $notice, EnrollmentCallRepository $enrollmentCallRepository)
    {
        $calls = $enrollmentCallRepository->callsByNotice($notice);
        return view('admin.notices.mail-send.edit', compact([
            'notice',
            'calls'
        ]));
    }


    public function mailSender(Request $request, Notice $notice)
    {
        $parameters = new ParametersRepository();

        $this->validate($request, [
            'subject' => 'required',
            'message' => 'required',
        ], [], [
            'subject'   => 'Assunto',
            'message'  => 'Mensagem'
        ]);

        $subscriptions = $notice->subscriptions()->with('user');
        if ($request->call_number) {
            $subsInCalls = collect();
            foreach ($notice->selectionCriterias as $selectionCriteria) {
                $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);                
                $subsInCall = DB::table($table)
                    ->select('subscription_id')
                    ->where('call_number','=',$request->call_number);
                    
                $subsInCall = ($request->status === 'pendente') ? $subsInCall->where('status','pendente')->get() : $subsInCall->get();
                $subsInCalls = $subsInCalls->merge($subsInCall->pluck('subscription_id'));
            }            
            $subscriptions->whereIn('id',$subsInCalls);              
        } 
        if ($request->is_ppi) {
            $ppiInWideConcurrency = collect();
            foreach ($notice->selectionCriterias as $selectionCriteria) {
                $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);                
                $ppiInWide = DB::table($table)
                    ->select('subscription_id')
                    ->where('is_wide_concurrency','=',true)
                    ->whereIn('status',['matriculado','pendente']);           
                $ppiInWideConcurrency = $ppiInWideConcurrency->merge($ppiInWide->pluck('subscription_id'));
            }            
            $subscriptions->whereNotIn('id',$ppiInWideConcurrency);      
            $subscriptions->whereHas('distributionOfVacancy.affirmativeAction', function ($q) use ($request) {
                $q->where('is_ppi', true);
            });
        }
        if ($request->only_subscribers) {
            $subscriptions->whereNull('is_homologated');
        }
        if ($request->only_homologated) {
            $subscriptions->where('is_homologated',true);
        }
        $subscriptions = $subscriptions->get();
        
        $batchMailSend = new BatchMailSend(
            $subscriptions->pluck('user.name')->sort()
        );
        Auth::user()->notify($batchMailSend);

        $count = $subscriptions->count();
        $pieces = ceil($count / 70);
        $i = 0;
        foreach ($subscriptions->split($pieces) as $part) {
            $when = now()->addMinutes(1 + $i++);
            Mail::to($parameters->getValueByName('email_instituicao'))
                ->bcc($part->pluck('user.email'))
                ->later($when, new CustomizedMail($request->subject, $request->message, $i, $pieces));
        }
        return back()->with('success', "Mandei $pieces e-mails, um total de $count pessoas");
    }
};
