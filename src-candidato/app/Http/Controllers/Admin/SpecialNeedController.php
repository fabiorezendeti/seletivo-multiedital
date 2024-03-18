<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Process\SpecialNeed;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpecialNeed;
use Illuminate\Database\QueryException;

class SpecialNeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $specialNeeds = SpecialNeed::where('description','ilike',"%$search%")->paginate();        
        return view('admin.special-needs.index',compact('specialNeeds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.special-needs.edit',[
            'specialNeed'=>new SpecialNeed()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpecialNeed $request)
    {
        $data = $request->all();
        $data['activated'] = $request->activated ?? 0;
        $data['require_details'] = $request->require_details ?? 0;
        $specialNeed = SpecialNeed::create($data);
        return redirect()->route('admin.special-needs.index')
            ->with('success',"A necessidade específica {$specialNeed->description} foi criada com sucesso");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Process\SpecialNeed  $specialNeed
     * @return \Illuminate\Http\Response
     */
    public function edit(SpecialNeed $specialNeed)
    {
        return view('admin.special-needs.edit',compact('specialNeed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Process\SpecialNeed  $specialNeed
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSpecialNeed $request, SpecialNeed $specialNeed)
    {
        $this->authorize('update',$specialNeed);
        $data = $request->all();
        $data['activated'] = $request->activated ?? 0;
        $data['require_details'] = $request->require_details ?? 0;
        $specialNeed->update($data);
        return redirect()->route('admin.special-needs.index')
            ->with('success',"A necessidade específica {$specialNeed->description} foi atualizada com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Process\SpecialNeed  $specialNeed
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecialNeed $specialNeed)
    {
        try {
            $description = $specialNeed->description;
            $specialNeed->delete();
            return redirect()->route('admin.special-needs.index')
                ->with('success',"A necessidade específica {$description} foi excluída com sucesso");
        } catch (QueryException $exception) {
            Log::error($exception->getMessage(),['Necessidades Específicas']);
            return  redirect()->route('admin.special-needs.index')
                ->with('error',"A necessidade específica {$description} não pode ser excluída");
        }
    }
}
