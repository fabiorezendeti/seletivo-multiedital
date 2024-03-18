<?php

namespace App\Http\Controllers;

use App\Models\Process\AffirmativeAction;
use App\Models\Process\DocumentType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\CommonMark\Block\Element\Document;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $documentTypes = DocumentType::where('description','ilike',"%$search%")
            ->orWhere('title',"$search")
            ->orderBy('title','desc')
            ->paginate();
        return view('admin.document-types.index', compact('documentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documentType = new DocumentType();
        $affirmativeActions = AffirmativeAction::orderBy('slug')->get();
        return view('admin.document-types.edit',compact('documentType','affirmativeActions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        DB::beginTransaction();        
        try {
            $data = $request->all();
            $data['active'] = $request->active ? 1 : 0;
            $data['sex'] = $request->sex ?? null;
            $data['age'] = $request->age ?? null;
            $data['required'] = $request->required ? 1 : 0;
            $documentType = DocumentType::create($data);
            $documentType->affirmativeActions()->attach($request->affirmative_actions);
            DB::commit();
            return redirect()
                ->route('admin.document-types.show',['document_type'=>$documentType]);        
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage(),['documentTypes']);
            DB::rollBack();
            return redirect()
                ->route('admin.document-types.index')
                ->with('error','Um erro ocorreu ao criar');
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Process\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentType $documentType)
    {        
        return view('admin.document-types.show',compact('documentType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Process\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentType $documentType)
    {
        $affirmativeActions = AffirmativeAction::orderBy('slug')->get();    
        return view('admin.document-types.edit',compact('documentType','affirmativeActions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Process\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentType $documentType)
    {        
        try {
            $data = $request->all();
            $data['active'] = $request->active ? 1 : 0;
            $data['required'] = $request->required ? 1 : 0;            
            $data['sex'] = $request->sex ?? null;
            $data['age'] = $request->age ?? null;
            $documentType->update($data);
            $documentType->affirmativeActions()->sync($request->affirmative_actions);
            return redirect()
                ->route('admin.document-types.show',['document_type'=>$documentType])
                ->with('success','Atualizado com sucesso');
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage(),['documentTypes']);
            return redirect()
                ->route('admin.document-types.show',['document_type'=>$documentType])
                ->with('error','Um erro ocorreu ao atualizar');
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Process\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentType $documentType)
    {
        try {            
            $documentType->delete();
            return redirect()
                ->route('admin.document-types.index')
                ->with('success','Excluido com sucesso');
        } catch (QueryException $queryException) {
            Log::error($queryException->getMessage(),['documentTypes']);
            return redirect()
                ->route('admin.document-types.show',['document_type'=>$documentType])
                ->with('error','Não conseguimos excluir, parece que este item está em uso');
        }       
    }
}
