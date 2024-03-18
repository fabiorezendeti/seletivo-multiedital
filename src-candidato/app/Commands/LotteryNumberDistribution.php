<?php

namespace App\Commands;

use App\Models\Process\Offer;
use App\Models\Process\Notice;
use Illuminate\Support\Facades\DB;
use App\Models\Process\Subscription;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class LotteryNumberDistribution
{

    public function distribute(Notice $notice)
    {
        set_time_limit(0);        
        $startTime = microtime(true);                
        $scoreTable = $this->createDatabaseStructure($notice);
        $offers = $this->getOffers($notice);
        foreach ($offers as $offer) {
            $subscriptions = $this->getSubscriptionsByOffer($offer);
            $this->createLotteryNumber($subscriptions, $scoreTable, $offer);
        }
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        $memoryUsage = memory_get_usage() /1048576.2;
        Log::info("Execution Time: {$executionTime} - Memory Usage: {$memoryUsage} MB",[
            'LOTTERY NUMBER DISTRIBUTION',
            'NOTICE ' . $notice->id
        ]);
    }

    private function createLotteryNumber(Collection $subscriptions, string $scoreTable, Offer $offer)
    {
        $number = 1;
        foreach ($subscriptions as $subscription) {            
            DB::table($scoreTable)->insert(
                [
                    'subscription_id' => $subscription->id,
                    'distribution_of_vacancies_id' => $subscription->distribution_of_vacancies_id,
                    'offer_id' => $offer->id,
                    'lottery_number' => $number,
                ]
            );
            $number++;
        }
    }

    private function getOffers(Notice $notice)
    {
        return $notice->offers()->whereHas('distributionVacancies', function ($q) {
            $q->whereLottery();
        })->get();
    }

    private function getSubscriptionsByOffer(Offer $offer)
    {
        return Subscription::select('subscriptions.*', 'users.name as user_name')
            ->join('users', 'users.id', '=', 'subscriptions.user_id')
            ->join(
                'distribution_of_vacancies',
                'distribution_of_vacancies.id',
                '=',
                'subscriptions.distribution_of_vacancies_id'
            )
            ->join('offers', 'offers.id', '=', 'distribution_of_vacancies.offer_id')
            ->where('subscriptions.notice_id', $offer->notice_id)
            ->where('distribution_of_vacancies.selection_criteria_id', 1)
            ->where('offers.id', $offer->id)
            ->isHomologated()
            ->IsNotEliminated()
            ->orderBy('users.name')
            ->get();
    }

    private function createDatabaseStructure(Notice $notice): string
    {
        $schemaName = $notice->getNoticeSchemaName();
        $scoreTable = $notice->getLotteryTable();
        $csi_ro = env('DB_CSI_RO_USERNAME');
        $csi_rw = env('DB_CSI_RW_USERNAME');
        $username = config('database.connections.pgsql.username');        
        DB::connection('pgsql-chef')->unprepared("CREATE SCHEMA IF NOT EXISTS {$schemaName}");
        DB::connection('pgsql-chef')->unprepared("GRANT USAGE ON schema {$schemaName} to {$username}");
        DB::connection('pgsql-chef')->unprepared("GRANT USAGE ON schema {$schemaName} to {$csi_ro}");
        DB::connection('pgsql-chef')->unprepared("GRANT ALL ON SCHEMA {$schemaName} to {$csi_rw}");


        try {
            Schema::connection('pgsql-chef')->create($scoreTable, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('subscription_id')->constrained();
                $table->foreignId('offer_id')->constrained();
                $table->integer('lottery_number');                
                $table->integer('global_position')->nullable();
                $table->integer('distribution_of_vacancy_position')->nullable();
                $table->unsignedBigInteger('distribution_of_vacancies_id')->nullable();

                $table->foreign('distribution_of_vacancies_id')
                    ->references('id')
                    ->on('distribution_of_vacancies');

                $table->unique('subscription_id');
                $table->unique(['offer_id', 'lottery_number']);
            });
            DB::connection('pgsql-chef')
                ->unprepared("GRANT select,insert,update,delete ON all tables in schema {$schemaName} to {$username}");
            DB::connection('pgsql-chef')
                ->unprepared("GRANT all ON all sequences in schema {$schemaName} to {$username}");
            DB::connection('pgsql-chef')
                ->unprepared("GRANT select,insert,update,delete ON all tables in schema {$schemaName} to {$csi_rw}");
            DB::connection('pgsql-chef')
                ->unprepared("GRANT all ON all sequences in schema {$schemaName} to {$csi_rw}");
            DB::connection('pgsql-chef')
                ->unprepared("GRANT select ON all tables in schema {$schemaName} to {$csi_ro}");
        } catch (QueryException $queryException) {
            Log::warning(
                "A tabela {$scoreTable} já existe . {$queryException->getMessage()}",
                ['LotteryNumberDistributionCommand LINE:', $queryException->getLine()]
            );
        }
        return $scoreTable;
    }
}
