<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process\ExamLocation;
use App\Models\Process\ExamRoomBooking;
use App\Models\Process\Notice;
use Exception;
use Illuminate\Http\Request;

class ExamRoomBookingsController extends Controller
{
    public function show(Notice $notice, ExamLocation $examLocation)
    {
        return view('admin.notices.allocation-of-exam-room.exam-locations.show',compact('notice', 'examLocation'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Notice $notice, ExamLocation $examLocation)
    {
        $examRoomBooking = new ExamRoomBooking();
        return view('admin.notices.allocation-of-exam-room.exam-rooms.edit',compact('notice','examLocation', 'examRoomBooking'));
    }

    public function edit(Notice $notice, ExamLocation $examLocation, ExamRoomBooking $examRoomBooking)
    {
        return view('admin.notices.allocation-of-exam-room.exam-rooms.edit',compact('notice','examLocation','examRoomBooking'));
    }

    public function destroy(Notice $notice, ExamLocation $examLocation, ExamRoomBooking $examRoomBooking)
    {
        try {
            $examRoomBooking->delete();
            return redirect()->route(
                'admin.notices.allocation-of-exam-room.exam_location',['notice'=>$notice,'exam_location'=>$examLocation]
            )->with('success','Sala excluída com sucesso');
        } catch (Exception $exception) {
            return redirect()->route(
                'admin.notices.allocation-of-exam-room.exam_location',['notice'=>$notice,'exam_location'=>$examLocation]
            )->with('error','Um erro ocorreu ao excluir a sala');
        }
    }

    public function store(Request $request, Notice $notice, ExamLocation $examLocation)
    {
        try {
            $data = $request->all();
            $data['notice_id'] = $notice->id;
            $data['exam_location_id'] = $examLocation->id;
            $data['active'] = $request->active ?? 0;
            $data['for_special_needs'] = $request->for_special_needs ?? 0;
            $examLocation->examRoomBookings()->create(
                $data
            );
            return redirect()->route(
                'admin.notices.allocation-of-exam-room.exam_location',['notice'=>$notice,'exam_location'=>$examLocation]
            )->with('success','Sala salva com sucesso!');
        } catch (Exception $exception) {
            return redirect()->route(
                'admin.notices.allocation-of-exam-room.exam_location',['notice'=>$notice,'exam_location'=>$examLocation]
            )->with('error','Um erro ocorreu ao salvar a sala!');
        }
    }

    public function update(Request $request, Notice $notice, ExamLocation $examLocation, ExamRoomBooking $examRoomBooking)
    {
        try {
            $data = $request->all();
            $data['notice_id'] = $notice->id;
            $data['exam_location_id'] = $examLocation->id;
            $data['active'] = $request->active ?? 0;
            $data['for_special_needs'] = $request->for_special_needs ?? 0;
            $examRoomBooking->update(
                $data
            );
            return redirect()->route(
                'admin.notices.allocation-of-exam-room.exam_location',['notice'=>$notice,'exam_location'=>$examLocation]
            )->with('success','Sala salva com sucesso!');
        } catch (Exception $exception) {
            return redirect()->route(
                'admin.notices.allocation-of-exam-room.exam_location',['notice'=>$notice,'exam_location'=>$examLocation]
            )->with('error','Um erro ocorreu ao salvar a sala!');
        }
    }
}
