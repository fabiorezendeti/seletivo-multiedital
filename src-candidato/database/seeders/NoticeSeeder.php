<?php

namespace Database\Seeders;

use App\Models\Course\Modality;
use Carbon\Carbon;
use App\Models\Process\Notice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Process\SelectionCriteria;
use App\Models\Process\CriteriaCustomization\Customization;

class NoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notice = Notice::create(
            [
                'number' => '01/2020',
                'modality_id' => Modality::inRandomOrder()->first()->id,
                'description' => 'Edital numero 01 do ano de 2020, seeder',
                'details' => 'Edital gerado pelo seeder, este campo é somente dealhes.',
                'link' => 'http://ifc.edu.br/',
                'subscription_initial_date' => '2020-10-01',
                'subscription_final_date' => '2020-12-30',
                'classification_review_initial_date' => '2020-11-02',
                'classification_review_final_date' => '2020-12-30',                
                'registration_fee' => 10.10,
                'payment_date' => '2020-12-30'
            ]
        );
        $notice->save();

        $selectionCriteria = SelectionCriteria::all();
        $notice->selectionCriterias()->attach($selectionCriteria->pluck('id'));        
        foreach($notice->selectionCriterias as $criteria) {
            $customization = new Customization($notice, $criteria);
            $customization->structureSave();
        }

        $notice = Notice::create(
            [
                'number' => '02/2020',
                'modality_id' => Modality::inRandomOrder()->first()->id,
                'description' => 'Edital numero 01 do ano de 2020, sorteio',
                'details' => 'Edital gerado pelo seeder, este campo é somente dealhes.',
                'link' => 'http://ifc.edu.br/',
                'subscription_initial_date' => '2020-10-01',
                'subscription_final_date' => '2020-12-30',
                'classification_review_initial_date' => '2020-11-02',
                'classification_review_final_date' => '2020-12-30',                
                'registration_fee' => 10.10,
                'payment_date' => '2020-12-30'
            ]
        );
        $notice->save();
        
        $notice->selectionCriterias()->attach([1]);        
        foreach($notice->selectionCriterias as $criteria) {
            $customization = new Customization($notice, $criteria);
            $customization->structureSave();
        }
        


        $notice = Notice::create(
            [
                'number' => '05/2020',
                'modality_id' => Modality::inRandomOrder()->first()->id,
                'description' => 'Edital numero 01 do ano de 2020, superior',
                'details' => 'Edital gerado pelo seeder, este campo é somente dealhes.',
                'link' => 'http://ifc.edu.br/',
                'subscription_initial_date' => '2020-10-01',
                'subscription_final_date' => '2020-12-30',
                'classification_review_initial_date' => '2020-11-02',
                'classification_review_final_date' => '2020-12-30',                
                'registration_fee' => 10.10,
                'payment_date' => '2020-12-30'
            ]
        );
        $notice->save();
        
        $notice->selectionCriterias()->attach([3,4]);        
        foreach($notice->selectionCriterias as $criteria) {
            $customization = new Customization($notice, $criteria);
            $customization->structureSave();
        }

        $notice = Notice::create(
            [
                'number' => '89/2021',
                'modality_id' => Modality::inRandomOrder()->first()->id,
                'description' => 'Edital numero 89 do ano de 2021, superior - Prova',
                'details' => 'Edital gerado pelo seeder, este campo é somente dealhes.',
                'link' => 'http://ifc.edu.br/',
                'subscription_initial_date' => '2020-10-01',
                'subscription_final_date' => '2021-12-30',
                'classification_review_initial_date' => '2021-11-02',
                'classification_review_final_date' => '2021-12-30',                
                'registration_fee' => 10.10,
                'payment_date' => '2021-12-30'
            ]
        );
        $notice->save();
        
        $notice->selectionCriterias()->attach([2]);        
        foreach($notice->selectionCriterias as $criteria) {
            $customization = new Customization($notice, $criteria);
            $customization->structureSave();
        }

    }
}
