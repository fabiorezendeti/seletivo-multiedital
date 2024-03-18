<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address\City;
use App\Models\Organization\Campus;
use App\Models\Process\ExamLocation;
use App\Models\Process\Notice;
use Exception;
use Illuminate\Http\Request;

class ExamLocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $examLocations = ($request->search) ?  ExamLocation::where('local_name','like',"%{$request->search}%")->paginate() : ExamLocation::paginate();
        return view('admin.exam-locations.index', compact('examLocations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $examLocation = new ExamLocation();
        $campuses = Campus::all();
        return view('admin.exam-locations.edit',compact('examLocation','campuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['address']['city'] = City::with('state')->find($request->city_id)->toArray();
        $data['active'] = $request->active ?? 0;
        $examLocation = ExamLocation::create($data);
        return redirect()->route('admin.process.exam-locations.show',['exam_location'=>$examLocation]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ExamLocation $examLocation)
    {
        return view('admin.exam-locations.show',compact('examLocation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamLocation $examLocation)
    {
        $campuses = Campus::all();
        return view('admin.exam-locations.edit',compact('examLocation','campuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamLocation $examLocation)
    {
        $data = $request->all();
        $data['address']['city'] = City::with('state')->find($request->city_id)->toArray();
        $data['active'] = $request->active ?? 0;
        $examLocation->update($data);
        return redirect()->route('admin.process.exam-locations.show',['exam_location'=>$examLocation]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamLocation $examLocation)
    {
        try {
            $examLocation->delete();
            return redirect()->route('admin.process.exam-locations.index')
                ->with('success','O local de prova foi excluído com sucesso');
        } catch (Exception $exception) {
            return redirect()->route('admin.process.exam-locations.show',['exam_location'=>$examLocation])
                ->with('error','Não é possível excluir este local de prova');
        }
    }

    /**
     * Relatório de local de prova resumido
     *
     * @param  ExamLocation $examLocation
     * @return \Illuminate\Http\Response
     */
    public function report(ExamLocation $examLocation)
    {
        return view('admin.exam-locations.report',compact('examLocation'));
    }

    /**
     * Relatório de local de prova por sala
     *
     * @param  ExamLocation $examLocation
     * @return \Illuminate\Http\Response
     */
    public function reportByRoom(ExamLocation $examLocation)
    {
        return view('admin.exam-locations.report-by-room',compact('examLocation'));
    }

   public function reportRoomBookingShort(Request $request, Notice $notice, ExamLocation $examLocation)
    {
        return view('admin.notices.allocation-of-exam-room.exam-locations.report',compact('notice','examLocation'));
    }

   public function reportRoomBooking(Notice $notice, ExamLocation $examLocation)
    {
        return view('admin.notices.allocation-of-exam-room.exam-locations.report-by-room',compact('notice','examLocation'));
    }
}
