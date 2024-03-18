<?php

namespace App\Http\Controllers\Admin;

use App\Commands\LotteryNumberDistribution;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Process\Notice;
use App\Http\Requests\StoreNotice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Course\Modality;
use Illuminate\Support\Facades\Auth;
use App\Models\Process\SelectionCriteria;
use App\Models\Process\CriteriaCustomization\Customization;
use Exception;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $notices = Notice::where('number', 'ilike', "%$search%")->orWhere('description', 'ilike', "%$search%")->orderBy('id','DESC')->paginate();
        return view('admin.notices.index', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notice = new Notice();
        $notice->gru_config = null;
        $notice->fill(
            [
                'subscription_initial_date' => Carbon::now(),
                'subscription_final_date' => Carbon::now()->add(30, 'day'),
                'classification_review_initial_date' => Carbon::now()->add(45, 'day'),
                'classification_review_final_date' => Carbon::now()->add(60, 'day'),
                'closed_at' => Carbon::now()->add(90, 'day')
            ]
        );
        $selectionCriterias = SelectionCriteria::orderBy('id')->get();
        $modalities = Modality::all();
        return view('admin.notices.edit', compact('notice', 'selectionCriterias', 'modalities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreNotice  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNotice $request)
    {
        $notice = Notice::create($request->all());
        $notice->selectionCriterias()->attach($request->selection_criteria);
        $this->makeCustomizations($notice);

        return redirect()->route('admin.notices.show', ['notice' => $notice])
            ->with('success', "Edital {$notice->number} Cadastrado com sucesso");
    }

    private function makeCustomizations(Notice $notice)
    {
        foreach ($notice->selectionCriterias as $selectionCriteria) {
            $customization = new Customization($notice, $selectionCriteria);
            $customization->structureSave();
        }
    }

    public function show(Request $request, Notice $notice)
    {
        $search = $request->search;
        $offers = $notice->offers()->with(['courseCampusOffer' => function ($query) {
            $query->with(['campus', 'course', 'shift']);
        }])->paginate();
        return view('admin.notices.show', compact('notice', 'offers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\Process\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function edit(Notice $notice)
    {
        $modalities = Modality::all();
        $selectionCriterias = SelectionCriteria::orderBy('id')->get();
        return view('admin.notices.edit', compact('notice', 'selectionCriterias', 'modalities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\StoreNotice  $request
     * @param  App\Models\Process\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function update(StoreNotice $request, Notice $notice)
    {
        $notice->fill($request->all());

        if (Auth::user()->can('updateCriteria', $notice)) {
            $notice->selectionCriterias()->sync($request->selection_criteria);
            $this->makeCustomizations($notice);
        }

        $notice->save();

        return redirect()
            ->route('admin.notices.show',['notice'=>$notice])
            ->with('success', "Edital {$notice->number} atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Process\Notice $notice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()
            ->route('admin.notices.index')
            ->with('success', "Edital {$notice->number} foi marcado como deletado!");
    }

    public function distributeLotteryNumber(Notice $notice, LotteryNumberDistribution $lotteryNumberCommand)
    {
        $this->authorize('distributeLotteryNumber', $notice);
        DB::beginTransaction();
        try {
            $lotteryNumberCommand->distribute($notice);
            DB::commit();
            return redirect()->route('admin.notices.show', ['notice' => $notice])
                ->with('success', 'Os números foram distribuidos com sucesso');
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), ['DISTRIBUTE LOTTERY NUMBER']);
            return back()->with('error', 'Um erro ocorreu ao distribuir os números');
        }
    }

    public function indexCards(Request $request)
    {
        $search = $request->search;
        $notices = Notice::where('number', 'ilike', "%$search%")->orWhere('description', 'ilike', "%$search%")->orderBy('id','DESC')->paginate();
        return view('manager.index', compact('notices'));
    }
}
