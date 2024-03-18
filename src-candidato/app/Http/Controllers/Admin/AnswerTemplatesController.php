<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process\AnswerCard;
use App\Models\Process\KnowledgeArea;
use App\Models\Process\Subscription;
use Response;
use Illuminate\Http\Request;
use App\Models\Process\Exam;
use App\Models\Process\Notice;
use App\Models\Process\AnswerTemplate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use File;

class AnswerTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Notice $notice, Exam $exam)
    {
        $answers = ($request->search) ?  AnswerTemplate::where('question_number','=',$request->search)->where('exam_id', $exam->id)->paginate() : AnswerTemplate::where('exam_id', $exam->id)->orderBy('question_number','asc')->paginate();
        $answer = new AnswerTemplate();
        //definindo valores default para ajudar o cadastrador de questões
        $next_question_number = AnswerTemplate::select(DB::raw("max(question_number) as q"))
                ->where('exam_id', $exam->id)
                ->first()->q;
        $answer->question_number = $next_question_number+1;
        $answer->weight = 1;
        $knowledgeAreas = KnowledgeArea::get();
        return view('admin.notices.exams.answer-templates.index', compact('notice','exam','answers','answer','knowledgeAreas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Notice $notice, Exam $exam, AnswerTemplate $answer)
    {
        try {
            $answer->question_number = $request->question_number;
            if($this->checkQuestionNumber($exam,$request->get('question_number'))>0){
                return  redirect()->route('admin.notices.exams.answers.index', compact('notice','exam','answer'))
                ->with('error',"Já existe uma questão cadastrada com o número {$request->get('question_number')} ");
            }
            $answer->right_answer = $request->right_answer;
            $answer->weight = $request->weight;
            $answer->exam_id = $exam->id;
            $answer->area_id = $request->area_id;
            $answer->save();
            $answer = new AnswerTemplate();
            $answers = AnswerTemplate::where('exam_id', $exam->id)->paginate();
            return redirect()->route('admin.notices.exams.answers.index', compact('notice','exam','answers','answer'))
                ->with('success',"Questão {$request->question_number} cadastrada :)");
        } catch (QueryException $exception) {
            Log::error($exception->getMessage(),['AnswerTemplate']);
            return  redirect()->route('admin.notices.exams.answers.index', compact('notice','exam','answer'))
                ->with('error',"Ocorreu um erro e a questão não pode ser alterada. Verifique os dados inseridos.");
        }
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
    public function edit(Notice $notice, Exam $exam, AnswerTemplate $answer)
    {
        $knowledgeAreas = KnowledgeArea::get();
        return view('admin.notices.exams.answer-templates.edit',compact('notice','exam','answer','knowledgeAreas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $notice , $exam, $answer)
    {
            try {
                $answer = AnswerTemplate::find($answer);
                $exam = Exam::find($answer->exam_id);
                //verifica se a questão que está sendo atualizada tem número diferente do original e se este número já existe em outra questão
                if($this->checkQuestionNumber($exam,$request->get('question_number'))>0 && ($answer->question_number!=$request->get('question_number'))){
                    return  redirect()->route('admin.notices.exams.answers.index', compact('notice','exam','answer'))
                    ->with('error',"Já existe uma questão cadastrada com o número {$request->get('question_number')} ");
                }
                $answer->update($request->all());
                if(!$request->has('is_canceled')){
                    $answer->update(array('is_canceled' => 'false'));
                }
                $notice = Notice::find($exam->notice_id);
                return redirect()->route('admin.notices.exams.answers.index', compact('notice','exam','answer'))
                    ->with('success',"A questão {$answer->question_number} foi atualizada.");
            }catch (QueryException $exception) {
                Log::error($exception->getMessage(),['AnswerTemplate']);
                return  redirect()->route('admin.notices.exams.answers.index', compact('notice','exam','answer'))
                    ->with('error',"Ocorreu um erro e a questão não pode ser alterada. Verifique os dados inseridos.");
            }

    }

    /**
     * Anula uma questão
     * */
    public function cancelAnswer(Request $request, $notice , $exam, $answer)
    {
        try {
            $answer = AnswerTemplate::find($answer);
            $answer->update(array('is_canceled' => 'true'));
            $exam = Exam::find($answer->exam_id);
            $notice = Notice::find($exam->notice_id);
            return redirect()->route('admin.notices.exams.answers.index', compact('notice','exam','answer'))
                ->with('success',"A questão {$answer->question_number} foi anulada.");
        }catch (QueryException $exception) {
            Log::error($exception->getMessage(),['AnswerTemplate']);
            return  redirect()->route('admin.notices.exams.answers.index', compact('notice','exam','answer'))
                ->with('error',"Ocorreu um erro e a questão não pode ser alterada. Verifique os dados inseridos.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($notice , $exam, $answer)
    {
        try {
            $answer = AnswerTemplate::find($answer);
            $exam = Exam::find($exam);
            $question = $answer->question_number;
            $notice = Notice::find($exam->notice_id);
            $answer->delete();
            return redirect()->route('admin.notices.exams.answers.index', compact('notice','exam','answer'))
                ->with('success',"A questão {$question} foi excluída com sucesso!");
        } catch (QueryException $exception) {
            Log::error($exception->getMessage(),['Exams']);
            return  redirect()->route('admin.notices.exams.answers.index', compact('notice','exam','answer'))
                ->with('error',"Ocorreu um erro e a questão não pode ser excluída.");
        }
    }

    private function checkQuestionNumber(Exam $exam, $q){
        return AnswerTemplate::where('question_number','=',$q)->where('exam_id', $exam->id)->count();
    }

    public function fakeGenerate(Notice $notice){
        $Exam = $notice->exam()->first();
        if(is_null($Exam))
            return  redirect()->back()->with('error',"Não há nenhum gabarito cadastrado");
        $AnswerTemplate = $Exam->answerTemplates()->select('right_answer');
        $template=$AnswerTemplate->get()->pluck('right_answer');
        $answers=$AnswerTemplate->groupBy('right_answer')->get()->pluck('right_answer')->toArray();
        array_push($answers, "*");
        $Subscriptions = $notice->subscriptions()->isHomologated()->select('subscription_number')->where('notice_id',$notice->id)->get();
        $headers = array(
            'Content-Type' => 'text/csv'
        );
        //I am storing the csv file in public >> files folder. So that why I am creating files folder
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        //creating the download file
        $filename =  public_path("files/download.csv");
        $handle = fopen($filename, 'w');
        //adding the data from the array
        foreach ($Subscriptions as $subscription) {
            $line = [];
            array_push($line, $subscription->subscription_number);
            $ausente = (random_int(9,1000) % 9) == 0;
            if($ausente){
                array_push($line, "F");
            }else{
                array_push($line, "_");
            }
            $rand_answers = array_map(function ($v) use ($answers, $ausente) {
                if($ausente) {
                    return "_";
                }

                if ((random_int(3,1000) % 3) == 0){
                    return $answers[random_int(0,count($answers)-1)];
                }

                return $v;
            }, $template->toArray());

            for ($i=0; $i < count($rand_answers); $i++){
                array_push($line, $rand_answers[$i]);
            }
            fputcsv($handle, $line, ';');
        }
        fclose($handle);
        //download command
        return Response::download($filename, "download.csv", $headers);

    }
}
