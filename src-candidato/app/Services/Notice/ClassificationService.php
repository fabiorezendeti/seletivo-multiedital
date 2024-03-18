<?php

namespace App\Services\Notice;

use Exception;
use Carbon\Carbon;
use App\Models\Process\Offer;
use App\Models\Process\Notice;
use App\Notifications\Subscribe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Process\Subscription;
use Illuminate\Support\Facades\Auth;
use App\Models\Process\SelectionCriteria;

class ClassificationService
{
    private Notice $notice;
    private SelectionCriteria $selectionCriteria;
    private string $scoreTableName;

    public function boot(Notice $notice, SelectionCriteria $selectionCriteria): void
    {
        $this->notice = $notice;
        $this->selectionCriteria = $selectionCriteria;
        $this->scoreTableName = $notice->getScoreTableNameForCriteriaId($selectionCriteria->id);
    }

    public function run(): void
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $offers = $this->notice->offers()->bySelectionCriteria($this->selectionCriteria)->get();
        $this->eliminate();
        foreach ($offers as $offer) {
            $scores = $this->notice->hasProva() ? $this->getScoresProva($offer->id) : $this->getScoresDefault($offer->id);
            $this->globalClassification($offer, $scores);
            $this->distributionClassification($offer, $scores);
        }
    }

    private function getScoresDefault($offer_id){
        return DB::table("$this->scoreTableName as scores")
            ->select(
                'scores.*',
                's.distribution_of_vacancies_id as s_distribution_of_vacancy_id',
                'dv.offer_id as dv_offer_id',
                'u.birth_date as u_birth_date'
            )
            ->join('core.subscriptions as s', 's.id', '=', 'scores.subscription_id')
            ->join('core.users as u', 's.user_id', '=', 'u.id')
            ->join('core.distribution_of_vacancies as dv', 's.distribution_of_vacancies_id', '=', 'dv.id')
            ->where('dv.offer_id', $offer_id)
            ->where('s.is_homologated', true)
            ->whereNull('s.elimination')
            ->orderBy('scores.media', 'desc')
            ->orderBy('u.birth_date', 'asc')
            ->get();
    }

    private function getScoresProva($offer_id){
        return DB::table("$this->scoreTableName as scores")
            ->select(
                'scores.*',
                's.distribution_of_vacancies_id as s_distribution_of_vacancy_id',
                'dv.offer_id as dv_offer_id',
                'u.birth_date as u_birth_date'
            )
            ->join('core.subscriptions as s', 's.id', '=', 'scores.subscription_id')
            ->join('core.users as u', 's.user_id', '=', 'u.id')
            ->join('core.distribution_of_vacancies as dv', 's.distribution_of_vacancies_id', '=', 'dv.id')
            ->where('dv.offer_id', $offer_id)
            ->where('s.is_homologated', true)
            ->whereNull('s.elimination')
            ->orderBy('scores.nota', 'desc')
            ->orderBy('scores.matematica_e_suas_tecnologias', 'desc')
            ->orderBy('scores.linguagens_codigos_e_tecnologias', 'desc')
            ->orderBy('scores.ciencias_da_natureza_e_suas_tecnologias', 'desc')
            ->orderBy('u.birth_date', 'asc')
            ->get();
    }

    private function eliminate()
    {
        if ($this->selectionCriteria->id === 4) return;
        $eliminated =
            Subscription::select('core.subscriptions.*')
            ->join("$this->scoreTableName as scores", 'core.subscriptions.id', '=', 'scores.subscription_id')
            ->whereNull('elimination')
            ->where(function ($q) {
                if(!$this->notice->hasSISU()) //SISU não tem coluna nota
                    $q->orWhere('scores.nota', 0);
                if(!$this->notice->hasProva())
                    $q->orWhere('scores.redacao', 0);
            })
            ->get();
        foreach ($eliminated as $subscription) {
            $subscription->setElimination(Carbon::now(), 'Não atingiu pontuação suficiente', Auth::user());
            $subscription->save();
        }
    }

    private function globalClassification(Offer $offer, $scores): void
    {
        for ($i = 0; $i < $scores->count(); $i++) {
            DB::table($this->scoreTableName)
                ->where('subscription_id', $scores[$i]->subscription_id)
                ->update([
                    'offer_id'                  => $scores[$i]->dv_offer_id,
                    'global_position'           => $i + 1,
                    'distribution_of_vacancies_id' => $scores[$i]->s_distribution_of_vacancy_id,
                    'is_tied'                   => $this->checkTie($scores, $i)
                ]);
        }
    }

    private function checkTie($scores, $position)
    {
        if($this->notice->hasProva())
            return $this->checkTieProva($scores, $position);

        if (empty($scores[$position - 1])) {
            $tiePrevious = false;
        } else {
            $tiePrevious = $scores[$position]->media === $scores[$position - 1]->media && $scores[$position]->u_birth_date === $scores[$position - 1]->u_birth_date;
        }
        if (empty($scores[$position + 1])) {
            $tieNext = false;
        } else {
            $tieNext = $scores[$position]->media === $scores[$position + 1]->media && $scores[$position]->u_birth_date === $scores[$position + 1]->u_birth_date;
        }
        return $tiePrevious || $tieNext;
    }

    private function checkTieProva($scores, $position){
        if (empty($scores[$position - 1])) {
            $tiePrevious = false;
        } else {
            $tiePrevious = $scores[$position]->nota === $scores[$position - 1]->nota;
        }
        if (empty($scores[$position + 1])) {
            $tieNext = false;
        } else {
            $tieNext = $scores[$position]->nota === $scores[$position + 1]->nota;
        }
        return $tiePrevious || $tieNext;
    }

    private function distributionClassification(Offer $offer, $scores): void
    {
        foreach ($offer->distributionVacancies as $distribution) {
            $filteredList = $scores->where('s_distribution_of_vacancy_id', $distribution->id);
            $position = 1;
            foreach ($filteredList as $score) {
                DB::table($this->scoreTableName)
                    ->where('subscription_id', $score->subscription_id)
                    ->update([
                        'distribution_of_vacancy_position'  => $position,
                    ]);
                $position++;
            }
            $position = 0;
        }
    }
}
