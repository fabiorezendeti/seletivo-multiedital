<?php

namespace App\Services\Lottery;

use App\Models\Process\Offer;
use Illuminate\Support\Facades\DB;

class DrawService
{

    public function save(Offer $offer, array $finalList)
    {
        $i = 1;
        foreach ($finalList as $subscription) {
            DB::table($offer->notice->getLotteryTable())
                ->where('subscription_id', $subscription['subscription_id'])
                ->where('offer_id',$offer->id)
                ->update([
                    'global_position' => $i
                ]);
            $i += 1;
        }
    
        $this->rankInTheDistributionOfVacancies($offer);
    }

    public function delete(Offer $offer)
    {                
            DB::table($offer->notice->getLotteryTable())
                ->where('offer_id',$offer->id)
                ->update([
                    'global_position' => null,
                    'distribution_of_vacancy_position' => null
                ]);
    }

    private function rankInTheDistributionOfVacancies(Offer $offer)
    {
        $list =  $offer->notice->getLotteryDraw($offer, $orderBy = 'global_position');
        $table = $offer->notice->getLotteryTable();

        foreach ($offer->distributionVacancies as $distribution ) {
            $filteredList = $list->where('subscription_distribution_of_vacancy_id',$distribution->id);
            $i = 1;            
            foreach ($filteredList as $item) {                            
                DB::table($table)
                    ->where('subscription_id', $item->subscription_id)
                    ->where('offer_id',$offer->id)
                    ->update([      
                        'distribution_of_vacancy_position' => $i
                    ]);
                $i += 1;
            }
        }
    }

}