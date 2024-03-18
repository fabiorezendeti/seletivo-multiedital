<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process\ExamLocation;
use App\Models\Process\ExamRoom;
use Exception;
use Illuminate\Http\Request;

class ExamRoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExamLocation $examLocation)
    {
        return $examLocation->examRooms->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ExamLocation $examLocation)
    {
        $examRoom = new ExamRoom();
        return view('admin.exam-locations.exam-rooms.edit',compact('examLocation','examRoom'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ExamLocation $examLocation)
    {        
        try {
            $data = $request->all();
            $data['active'] = $request->active ?? 0;
            $data['for_special_needs'] = $request->for_special_needs ?? 0;
            $examLocation->examRooms()->create(
                $data
            );
            return redirect()->route(
                'admin.process.exam-locations.show',['exam_location'=>$examLocation]
            )->with('success','Sala salva com sucesso');
        } catch (Exception $exception) {
            return redirect()->route(
                'admin.process.exam-locations.show',['exam_location'=>$examLocation]
            )->with('error','Um erro ocorreu ao salvar a sala');
        }
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamLocation $examLocation, ExamRoom $examRoom)
    {
        return view('admin.exam-locations.exam-rooms.edit',compact('examLocation','examRoom'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamLocation $examLocation, ExamRoom $examRoom)
    {        
        try {
            $data = $request->all();
            $data['active'] = $request->active ?? 0;  
            $data['for_special_needs'] = $request->for_special_needs ?? 0;  
            $examRoom->update(
                $data
            );
            return redirect()->route(
                'admin.process.exam-locations.show',['exam_location'=>$examLocation]
            )->with('success','Sala salva com sucesso');
        } catch (Exception $exception) {
            return redirect()->route(
                'admin.process.exam-locations.show',['exam_location'=>$examLocation]
            )->with('error','Um erro ocorreu ao salvar a sala');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamLocation $examLocation, ExamRoom $examRoom)
    {
        try {
            $examRoom->delete();
            return redirect()->route(
                'admin.process.exam-locations.show',['exam_location'=>$examLocation]
            )->with('success','Sala excluída com sucesso');
        } catch (Exception $exception) {
            return redirect()->route(
                'admin.process.exam-locations.show',['exam_location'=>$examLocation]
            )->with('error','Um erro ocorreu ao excluir a sala');
        }
    }
}
