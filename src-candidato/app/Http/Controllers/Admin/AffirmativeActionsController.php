<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAffirmativeActions;
use App\Models\Course\Modality;
use App\Models\Process\AffirmativeAction;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AffirmativeActionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $affirmativeActions = AffirmativeAction::where('description','ilike',"%$search%")
            ->orWhere('slug',"$search")
            ->orderBy('classification_priority','desc')
            ->paginate();
        return view('admin.affirmative-actions.index',compact('affirmativeActions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modalities = Modality::all();
        return view('admin.affirmative-actions.edit',[
            'affirmativeAction' => new AffirmativeAction(),
            'modalities' => $modalities
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAffirmativeActions $request)
    {
        $affirmativeAction = AffirmativeAction::create($request->all());
        $affirmativeAction->modalities()->sync($request->modalities);
        return redirect()->route('admin.affirmative-actions.index')->with(
            'success' , "A ação {$affirmativeAction->slug} foi cadastrada com sucesso"
        );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Process\AffirmativeActions  $affirmativeActions
     * @return \Illuminate\Http\Response
     */
    public function edit(AffirmativeAction $affirmativeAction)
    {
        $this->authorize('editOrDelete',$affirmativeAction);
        $modalities = Modality::all();
        return view('admin.affirmative-actions.edit',
            compact('affirmativeAction','modalities')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Process\AffirmativeActions  $affirmativeActions
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAffirmativeActions $request, AffirmativeAction $affirmativeAction)
    {
        $this->authorize('editOrDelete',$affirmativeAction);        
        $affirmativeAction->is_wide_competition = ($request->is_wide_competition) ? true : false;
        $affirmativeAction->is_ppi = ($request->is_ppi) ? true : false;
        $affirmativeAction->update($request->all());
        $affirmativeAction->modalities()->sync($request->modalities);
        return redirect()->route('admin.affirmative-actions.index')
            ->with('success',"A ação afirmativa $affirmativeAction->slug foi atualizada com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Process\AffirmativeActions  $affirmativeActions
     * @return \Illuminate\Http\Response
     */
    public function destroy(AffirmativeAction $affirmativeAction)
    {
        try {
            DB::beginTransaction();
            $this->authorize('editOrDelete',$affirmativeAction);
            $slug = $affirmativeAction->slug;
            $affirmativeAction->modalities()->detach();
            $affirmativeAction->delete();
            DB::commit();
            return redirect()->route('admin.affirmative-actions.index')
                ->with('success',"A ação afirmativa $slug foi excluída com sucesso");
        } catch (QueryException $exception) {
            DB::rollBack();
            return back()->with('error','A ação não pode ser excluída, já está vinculada a editais');
        }
    }
}
