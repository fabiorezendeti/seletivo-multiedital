<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Process\ExamResource;
use Illuminate\Database\QueryException;
use App\Http\Requests\StoreExamResource;

class ExamResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $examResources = ExamResource::where('description','ilike',"%$search%")->paginate();        
        return view('admin.exam-resources.index',compact('examResources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.exam-resources.edit',[
            'examResource'=>new ExamResource()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamResource $request)
    {
        $data = $request->all();
        $data['activated'] = $request->activated ?? 0;
        $data['require_details'] = $request->require_details ?? 0;
        $examResource = ExamResource::create($data);
        return redirect()->route('admin.exam-resources.index')
            ->with('success',"O recurso {$examResource->description} foi criada com sucesso");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Process\ExamResource  $ExamResource
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamResource $examResource)
    {
        return view('admin.exam-resources.edit',compact('examResource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Process\ExamResource  $ExamResource
     * @return \Illuminate\Http\Response
     */
    public function update(StoreExamResource $request, ExamResource $examResource)
    {
        $this->authorize('update',$examResource);
        $data = $request->all();
        $data['activated'] = $request->activated ?? 0;
        $data['require_details'] = $request->require_details ?? 0;
        $examResource->update($data);
        return redirect()->route('admin.exam-resources.index')
            ->with('success',"A necessidade específica {$examResource->description} foi atualizada com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Process\ExamResource  $ExamResource
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamResource $examResource)
    {
        try {
            $description = $examResource->description;
            $examResource->delete();
            return redirect()->route('admin.exam-resources.index')
                ->with('success',"A necessidade específica {$description} foi excluída com sucesso");
        } catch (QueryException $exception) {
            Log::error($exception->getMessage(),['Necessidades Específicas']);
            return  redirect()->route('admin.exam-resources.index')
                ->with('error',"A necessidade específica {$description} não pode ser excluída");
        }
    }
}