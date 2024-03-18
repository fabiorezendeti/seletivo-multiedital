<?php

namespace App\Http\LiveComponents\Admin;

use App\Models\Process\Notice;
use Livewire\Component;

class NoticeOptions extends Component
{
    public $noticeId;
    private $notices;
    private $offers = [];

    // public function mount($noticeId = null)
    // {
    //     $this->notices = Notice::all();
    //     $notice = ($noticeId) ? Notice::find($noticeId) : $this->notices->first();
    //     $this->noticeId =  $notice->id;

    //     $this->offers = $notice->offers()
    //         ->with(['courseCampusOffer' => function ($q) {
    //             $q->with(['campus']);
    //         }])->get();

    // }

    public function render()
    {
        // $noticeId = null;
        $this->notices = Notice::all();
        $notice = ($this->noticeId) ? Notice::find($this->noticeId) : $this->notices->first();
        // $this->noticeId =  $notice->id;

        $this->offers = $notice->offers()
            ->with(['courseCampusOffer' => function ($q) {
                $q->with(['campus']);
            }])->get();
        return view('live-components.admin.notice-options', ['notices' => $this->notices, 'offers' => $this->offers]);
    }

}
