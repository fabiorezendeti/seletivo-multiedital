<?php

namespace App\Repository;

use App\Models\Course\CampusOffer;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use Illuminate\Support\Facades\Request;

class OfferRepository
{

    public function create(Notice $notice, array $data)
    {
        return $notice->offers()->create($data);
    }

    public function update(Notice $notice, Offer $offer, array $data)
    {
        $notice->offers()->updateOrCreate(
            ['id' => $offer->id],
            $data
        );
    }

    public function updateByCourseCampusOffer(Notice $notice,int $course_campus_offer_id, array $data)
    {
        return $notice->offers()->updateOrCreate(
            ['course_campus_offer_id' => $course_campus_offer_id],
            $data
        );
    }
    

    public function delete(Notice $notice, Offer $offer)
    {        
        $notice->offers()->where('id',$offer->id)->delete();
    }
}
