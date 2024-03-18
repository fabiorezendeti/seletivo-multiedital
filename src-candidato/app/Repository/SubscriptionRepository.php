<?php

namespace App\Repository;

use App\Commands\SubscriptionFreeze;
use App\Models\Process\DistributionOfVacancies;
use Illuminate\Http\Request;
use App\Models\Process\Offer;
use App\Models\Process\Notice;
use Illuminate\Support\Facades\DB;
use App\Models\Process\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mockery\Matcher\Not;

class SubscriptionRepository
{

    private SubscriptionFreeze $subscriptionFreeze;

    public function __construct(SubscriptionFreeze $subscriptionFreeze)
    {
        $this->subscriptionFreeze = $subscriptionFreeze;
    }

    private function makeSubscription(Subscription $subscription, Offer $offer, Notice $notice, Request $request) : Subscription
    {
        $subscription = $subscription->updateOrCreate(
            [
                'notice_id' => $offer->notice_id,
                'user_id'   => Auth::id()
            ],
            [
                'distribution_of_vacancies_id' => $request->distribution_of_vacancies,
                'is_homologated' => (!$notice->hasFee()) ?  true : null,
                'special_need_id' => $request->special_need_id,
                'special_need_description' => $request->special_need_description,
                'exam_resource_id' => $request->exam_resource_id,
                'exam_resource_description' => $request->exam_resource_description,
                'additional_test_time' => $request->additional_test_time ?? false,                
            ]
        );                        
        $subscription->additional_test_time_analysis = ($request->additional_test_time) ? $subscription->getAdditionalTestTimeAnalysisTemplate() : null;        
        $subscription->exam_resource_analysis = ($request->exam_resource_id) ? $subscription->getExamResourceAnalysisTemplate() : null;
        $subscription->subscription_number = $subscription->makeSubscriptionNumber($notice, Auth::user());
        $subscription->save();       
        return $subscription;
    }
    
    private function makeSubscriptionFromEnem(Subscription $subscription, User $user, Notice $notice, DistributionOfVacancies $distributionOfVacancies, $data) : Subscription
    {
        $subscription = $subscription->updateOrCreate(
            [
                'notice_id' => $distributionOfVacancies->offer->notice_id,
                'user_id'   => $user->id
            ],
            [
                'distribution_of_vacancies_id' => $distributionOfVacancies->id,
                'is_homologated' => null,
                'special_need_id' => null,
                'special_need_description' => null,
                'exam_resource_id' => null,
                'exam_resource_description' => null,
                'additional_test_time' => false,                
            ]
        );                                
        $subscription->subscription_number = $data['CO_INSCRICAO_ENEM'];
        $subscription->save();       
        return $subscription;
    }


    private function upgradeCustomizableTables(Request $request, Subscription $subscription, Notice $notice, $fileName = null)
    {
        if ($request->selection_criteria == 3) {
            DB::table($notice->getScoreTableNameForCriteriaId(3))->updateOrInsert(
                ['subscription_id' => $subscription->id],
                [
                    'ano_do_enem'   =>  $request->criteria_3_ano_do_enem,
                    'linguagens_codigos_e_tecnologias'   => str_replace(',', '.', $request->criteria_3_linguagens_codigos_e_tecnologias),
                    'matematica_e_suas_tecnologias' => str_replace(',', '.', $request->criteria_3_matematica_e_suas_tecnologias),
                    'ciencias_humanas_e_suas_tecnologias' => str_replace(',', '.', $request->criteria_3_ciencias_humanas_e_suas_tecnologias),
                    'ciencias_da_natureza_e_suas_tecnologias' => str_replace(',', '.', $request->criteria_3_ciencias_da_natureza_e_suas_tecnologias),
                    'redacao' => str_replace(',', '.', $request->criteria_3_redacao),
                    'media' => str_replace(',', '.', $request->criteria_3_media),
                    'documento_comprovacao' => $fileName
                ]
            );
            return;
        }
        if ($request->selection_criteria == 4) {
            
            DB::table($notice->getScoreTableNameForCriteriaId(4))->updateOrInsert(
                ['subscription_id' => $subscription->id],
                [
                    'modalidade'   =>  $notice->getModalitiesForCurriculumAnalisys()[$request->criteria_4_modalidade]->title,
                    'linguagens_codigos_e_tecnologias'   => ($request->criteria_4_modalidade > 1) ? str_replace(',', '.', $request->criteria_4_linguagens_codigos_e_tecnologias) : null,
                    'matematica_e_suas_tecnologias' => ($request->criteria_4_modalidade > 1) ? str_replace(',', '.', $request->criteria_4_matematica_e_suas_tecnologias) : null,
                    'ciencias_humanas_e_suas_tecnologias' => ($request->criteria_4_modalidade > 1) ? str_replace(',', '.', $request->criteria_4_ciencias_humanas_e_suas_tecnologias) : null,
                    'ciencias_da_natureza_e_suas_tecnologias' => ($request->criteria_4_modalidade > 1) ? str_replace(',', '.', $request->criteria_4_ciencias_da_natureza_e_suas_tecnologias) : null,
                    'media' => str_replace(',', '.', ($request->criteria_4_modalidade > 1) ? $request->criteria_4_media_certificacao : $request->criteria_4_media_regular),
                    'documento_comprovacao' => $fileName
                ]
            );
            return;
        }        
    }

    public function upgradeCustomizableTablesFromSISU(array $data, Subscription $subscription, Notice $notice, $fileName)
    {
        DB::table($notice->getScoreTableNameForCriteriaId(5))->updateOrInsert(
            ['subscription_id' => $subscription->id],
            [
                'ano_do_enem'   =>  Carbon::now()->format('Y'),
                'linguagens_codigos_e_tecnologias'   => str_replace(',', '.', $data['NU_NOTA_L']),
                'matematica_e_suas_tecnologias' => str_replace(',', '.', $data['NU_NOTA_M']),
                'ciencias_humanas_e_suas_tecnologias' => str_replace(',', '.', $data['NU_NOTA_CH']),
                'ciencias_da_natureza_e_suas_tecnologias' => str_replace(',', '.', $data['NU_NOTA_CN']),
                'redacao' => str_replace(',', '.', $data['NU_NOTA_R']),
                'media' => str_replace(',', '.', $data['NU_NOTA_CANDIDATO']),
                'documento_comprovacao' => $fileName
            ]
        );
        return;
    }

    private function fileStorage(Request $request, Subscription $subscription, Notice $notice)
    {
        if ($request->selection_criteria > 2 && $request->hasFile('documento_comprovacao')) {
            $file = $request->file('documento_comprovacao');
            $fileName = $subscription->id . '.' . $file->getClientOriginalExtension();
            $file->storeAs($notice->getNoticeSchemaName(), $fileName);
            return $fileName;
        }
        return $subscription->getScore()->documento_comprovacao ?? null;
    }

    private function fileStorageFromSISU(array $data, Subscription $subscription, Notice $notice)
    {
        $fileName = $subscription->id . '.json';
        Storage::put($notice->getNoticeSchemaName().'/'.$fileName,json_encode($data,JSON_PRETTY_PRINT));
        return $fileName;
    }

    public function updateOrCreateSubscriptionFromSISU(Notice $notice, Offer $offer, DistributionOfVacancies $distributionOfVacancies, User $user, $data)
    {   
        $subscription = Subscription::where('notice_id', $notice->id)->where('user_id', $user->id)->first() ?? new Subscription();   
        $this->resetSubscriptionIfCriteriaCustomized($subscription, $notice, $distributionOfVacancies->selection_criteria_id);
        $subscription = $this->makeSubscriptionFromEnem($subscription, $user, $notice, $distributionOfVacancies, $data);
        $fileName = $this->fileStorageFromSISU($data, $subscription, $notice);
        $this->upgradeCustomizableTablesFromSISU($data, $subscription, $notice, $fileName);
        $this->subscriptionFreeze->freeze($subscription);
        return $subscription;
    }
    
    

    public function updateOrCreateSubscription(Subscription $subscription, Notice $notice, Request $request, Offer $offer)
    {   
        $this->resetSubscriptionIfCriteriaCustomized($subscription, $notice, $request->selection_criteria);
        if ($request->special_need_id == 0 or !$request->special_need_id) {
            $request->special_need_id = null;
            $request->special_need_description = null; 
        } 
        if ($request->exam_resource_id == 0 or !$request->exam_resource_id) {
            $request->exam_resource_id = null;
            $request->exam_resource_description = null; 
        } 
        $subscription = $this->makeSubscription(
            $subscription,
            $offer,
            $notice,
            $request
        );
        $fileName = $this->fileStorage($request, $subscription, $notice);
        $this->upgradeCustomizableTables($request, $subscription, $notice, $fileName);
        $this->subscriptionFreeze->freeze($subscription);
        return $subscription;
    }

    private function resetSubscriptionIfCriteriaCustomized(Subscription $subscription, Notice $notice, $selectionCriteriaId)
    {
        if (!$subscription->id) return;
        if (
            $subscription->distributionOfVacancy->selection_criteria_id > 2
            && $subscription->distributionOfVacancy->selection_criteria_id != $selectionCriteriaId
        ) {
            DB::table(
                $notice->getScoreTableNameForCriteriaId(
                    $subscription->distributionOfVacancy->selection_criteria_id
                )
            )
                ->where('subscription_id', $subscription->id)
                ->delete();
        }
    }


}