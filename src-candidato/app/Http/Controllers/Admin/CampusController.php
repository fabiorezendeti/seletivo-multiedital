<?php

namespace App\Http\Controllers\Admin;

use App\Models\Organization\Campus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampus;

class CampusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campi = Campus::orderBy('id')->paginate();
        return view('admin.campuses.index', compact('campi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $campus = new Campus();
        return view('admin.campuses.edit', compact('campus'));
    }

    public function show(Campus $campus)
    {
        $campus->city->state;
        return $campus->toJson();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\CampusRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCampus $request)
    {        
        $campus = Campus::create($request->all());
        return redirect()->route('admin.campuses.index')
            ->with('success', "Campus {$campus->name} Cadastrado com sucesso");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Organization\Campus  $campus
     * @return \Illuminate\Http\Response
     */
    public function edit(Campus $campus)
    {
        return view('admin.campuses.edit', compact('campus'));
    }

    /**
     * Update the specified resource in storage.
     *∂
     * @param  \Illuminate\Http\Requests\CampusRequest  $request
     * @param  \App\Models\Organization\Campus  $campus
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCampus $request, Campus $campus)
    {
        if (Auth::user()->cannot('updateCampus', $campus)) {
            return redirect()
                ->route('admin.campuses.index')
                ->with('error', 'Não pode alterar os dados deste campus!');
        }
        $campus->name = $request->name;
        $campus->email = $request->email;
        $campus->site = $request->site;
        $campus->street = $request->street;
        $campus->number = $request->number;
        $campus->district = $request->district;
        $campus->zip_code = $request->zip_code;
        $campus->phone_number = $request->phone_number;
        $campus->city_id = $request->city_id;
        $campus->save();
        return redirect()
            ->route('admin.campuses.index')
            ->with('success', "Campus {$campus->name} atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization\Campus  $campus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campus $campus)
    {
        if (Auth::user()->cannot('deleteCampus', $campus)) {
            return redirect()
                ->route('admin.campuses.index')
                ->with('error', 'Não pode deletar este campus!');
        }
        $name = $campus->name;
        $campus->delete();
        return redirect()
            ->route('admin.campuses.index')
            ->with('success', "Campus $name excluído com sucesso.");
    }
}
