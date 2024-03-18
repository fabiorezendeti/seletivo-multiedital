<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Course\Modality;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModality;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ModalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $modalities = Modality::where('description','ilike',"%$search%")->paginate();        
        return view('admin.modalities.index',compact('modalities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.modalities.edit',[
            'modality'=>new Modality()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreModality $request)
    {
        $modality = Modality::create($request->all());
        return redirect()->route('admin.modalities.index')
            ->with('success',"A modalidade {$modality->description} foi criada com sucesso");
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course\Modality  $modality
     * @return \Illuminate\Http\Response
     */
    public function edit(Modality $modality)
    {
        return view('admin.modalities.edit',compact('modality'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course\Modality  $modality
     * @return \Illuminate\Http\Response
     */
    public function update(StoreModality $request, Modality $modality)
    {
        $modality->update($request->all());
        return redirect()->route('admin.modalities.index')
            ->with('success',"A modalidade {$modality->description} foi atualizada com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course\Modality  $modality
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modality $modality)
    {
        try {
            $description = $modality->description;
            $modality->delete();
            return redirect()->route('admin.modalities.index')
                ->with('success',"A modalidade {$description} foi excluída com sucesso");
        } catch (QueryException $exception) {
            Log::error($exception->getMessage(),['Modalidades']);
            return  redirect()->route('admin.modalities.index')
                ->with('error',"A modalidade {$description} não pode ser excluída");
        }
    }
}
