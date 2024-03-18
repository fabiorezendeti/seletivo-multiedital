<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process\AffirmativeAction;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MigrationVacancyMapController extends Controller
{

    public function index(AffirmativeAction $affirmativeAction)
    {
        $availableAffirmativeActions = AffirmativeAction::where('id', '!=', $affirmativeAction->id)
            ->whereNotIn('id',$affirmativeAction->migrationVacancyMap->pluck('affirmative_action_to_id'))
            ->whereHas('modalities', function ($q) use ($affirmativeAction) {
                $q->whereIn('id', $affirmativeAction->modalities->pluck('id'));
            })                        
            ->orderBy('classification_priority')
            ->get();
        return view('admin.affirmative-actions.migration-map.index', compact(
            'affirmativeAction',
            'availableAffirmativeActions'
        ));
    }


    public function store(Request $request, AffirmativeAction $affirmativeAction)
    {
        $this->validate(
            $request,
            [
                'affirmative_action_to_id' => ['required'],
                'order'                    => ['required','integer']
            ],[],[
                'affirmative_action_to_id' => 'Ação Afirmativa',
                'order'                    => 'Ordem'
            ]
        );
        try {
            $affirmativeAction->migrationVacancyMap()->create(
                [
                    'affirmative_action_to_id'  => $request->affirmative_action_to_id,
                    'order' => $request->order
                ]
            );
            return redirect()->back()->with('success','Adicionado com sucesso');
        } catch (QueryException $exception) {
            Log::error($exception->getMessage(),['MIGRATION VACANCY MAP']);
            return redirect()->back()->with('error','Um erro ocorreu');
        }        
    }
}
