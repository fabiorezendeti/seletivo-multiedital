<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process\Notice;
use App\Models\Process\Subscription;
use App\Repository\CampusRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamResourceAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Notice $notice)
    {
        $subscriptions = $notice->subscriptions()->hasExamResourceRequest()->get();
        return view('admin.notices.exam-resources-analysis.index',compact(
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
        $this->authorize('allowExamResourceAnalysis',$subscription);
        $user = Auth::user();
        $subscription->forceFill([
            'exam_resource_analysis->feedback'=>$request->feedback,
            'exam_resource_analysis->approved'=>$request->approved,
            'exam_resource_analysis->approved_ptBR'=>$request->approved ? 'Sim' : 'Não',
            'exam_resource_analysis->user'=> [
                'uuid'  => $user->uuid,
                'name'  => $user->name,
                'cpf'   => $user->cpf
            ],
        ]);
        $subscription->save();
        return response($subscription->exam_resource_analysis,200);
    }

    public function report(Notice $notice, Request $request)
    {
        $CampusRepository = new CampusRepository();
        $campuses = $CampusRepository->getCampusesByNotice($notice);
        if (!$request->html) {

            return view('admin.reports.exam-resources-analysis', compact('notice', 'campuses'));
        }

        $dados = Subscription::select(
            'subscriptions.subscription_number as inscricao',
            'subscriptions.exam_resource_analysis as analise',
            'u.name as candidato',
            'c.name as campus',
            'courses.name as curso',
            'er.description as recurso'
        )
            ->join('distribution_of_vacancies AS d', 'subscriptions.distribution_of_vacancies_id','=', 'd.id')
            ->join('offers AS o', 'd.offer_id', '=', 'o.id')
            ->join('course_campus_offers AS cco', 'o.course_campus_offer_id', '=', 'cco.id')
            ->join('courses', 'cco.course_id','=','courses.id')
            ->join('campuses AS c', 'cco.campus_id','=','c.id')
            ->join('users AS u', 'subscriptions.user_id', '=', 'u.id')
            ->join('exam_resources AS er', 'subscriptions.exam_resource_id', '=', 'er.id')
            ->where('subscriptions.notice_id', '=', $notice->id)
            ->orderBy('c.name', 'ASC')
            ->orderBY('courses.name', 'ASC')
            ->orderBy('u.name', 'ASC');
        if(!empty($request->campus)) $dados = $dados->where('c.id', $request->campus);
        $dados = $dados->get();
        /**
         * ESTRUTURA DOS DADOS ENVIADOS A BLADE:
         * "inscricao" => 2213122933
         * "candidato" => "Ryan Sued"
         * "campus" => "Blumenau"
         * "status" => false
         * "recurso" => LEITOR(A)"
         */
        return view('admin.reports.html.exam-resources-analysis', compact('notice', 'dados'));

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
