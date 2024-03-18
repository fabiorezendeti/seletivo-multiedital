<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Process\Exam;
use App\Models\Process\Notice;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Notice $notice)
    {
        $exams = ($request->search) ?  Exam::where('title','ilike',"%{$request->search}%")->where('notice_id', $notice->id)->paginate() : Exam::where('notice_id', $notice->id)->paginate();
        return view('admin.notices.exams.index', compact('notice','exams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Notice $notice)
    {
        if($notice->hasExam()){
            return redirect()->route('admin.notices.exams.index', compact('notice'))
               ->with('error',"O edital {$notice->number} já possui gabarito cadastrado.");
        }
        $exam = new Exam();
        return view('admin.notices.exams.edit',compact('exam','notice'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Notice $notice)
    {
        $exam = $notice->exam()->firstOrNew([]);
        $exam->title = $request->title;
        $notice->exam()->save($exam);

        return redirect()->route('admin.notices.exams.index', compact('notice'))
            ->with('success',"O gabarito {$exam->title} foi criado. Agora você pode cadastrar as questões/respostas.");
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
    public function edit(Notice $notice, Exam $exam)
    {
        return view('admin.notices.exams.edit',compact('exam','notice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $notice , $exam)
    {
        $exam = Exam::find($exam);
        $exam->update($request->all());
        $notice = Notice::find($exam->notice_id);
        return redirect()->route('admin.notices.exams.index', compact('notice'))
            ->with('success',"O gabarito {$exam->title} foi atualizado.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($notice , $exam)
    {
        try {
            $exam = Exam::find($exam);
            $title = $exam->title;
            $notice = Notice::find($exam->notice_id);
            $exam->delete();
            return redirect()->route('admin.notices.exams.index', compact('notice'))
                ->with('success',"O gabarito {$title} foi excluído com sucesso!");
        } catch (QueryException $exception) {
            Log::error($exception->getMessage(),['Exams']);
            return  redirect()->route('admin.notices.exams.index', compact('notice'))
                ->with('error',"O gabarito {$title} não pode ser excluído.");
        }
    }
}
