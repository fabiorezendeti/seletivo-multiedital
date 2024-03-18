<?php

namespace App\Repository;

use App\Models\Organization\Campus;
use App\Models\Process\Notice;

class CampusRepository
{

    public function getCampusesByNotice(Notice $notice)
    {
        return Campus::withVacanciesByNotice($notice)->get();
    }

    public function getCampusesByNoticeWithCourseOffers(Notice $notice)
    {
        return Campus::whereHas('courseOffers.course.modality', function ($q) use ($notice) {
            $q->where('id', $notice->modality_id);
        })
            ->with('courseOffers',function($q) use ($notice) {
                $q->whereHas('course', function ($q) use ($notice) {
                    $q->where('modality_id',$notice->modality_id);
                });
                $q->with(['shift','course.modality']);
            })->get();            
    }
}
