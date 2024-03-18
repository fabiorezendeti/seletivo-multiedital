<?php

namespace App\Services\Notice;

use App\Models\Process\DistributionOfVacancies;
use App\Models\Process\EnrollmentSchedule;
use App\Models\Process\MigrationVacancyMap;
use Exception;

use App\Models\Process\Offer;
use App\Models\Process\Notice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\Process\SelectionCriteria;
use App\Models\Process\Subscription;
use App\Repository\EnrollmentCallRepository;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;

class EnrollmentCallService
{

    private EnrollmentCallRepository $enrollmentCallRepository;
    private string $callTableName;
    private array $vacancyTable;
    private array $subscriptionsInCall = [];
    private $protectedAffirmativeActions = 'N';

    public function __construct(EnrollmentCallRepository $enrollmentCallRepository)
    {
        $this->enrollmentCallRepository = $enrollmentCallRepository;
    }

    /**
     * Inicia a estrutura de banco de dados se ela não existir
     */
    public function boot(Notice $notice)
    {
        $this->makeDatabaseToNoticeCallsIfNotExists($notice);
        $date = Carbon::createFromDate(2021, 8, 30);
        if ($notice->created_at->lt($date)) $this->createEnrollmentTable($notice);
    }

    private function makeDatabaseToNoticeCallsIfNotExists(Notice $notice)
    {
        if ($notice->enrollment_call_table_created) return;
        $this->createSchema($notice);
        $this->createTables($notice);
        $this->createEnrollmentTable($notice);
        $this->grantPrivileges($notice);
    }

    private function createTables(Notice $notice)
    {
        //dd('passou aqui');
        foreach ($notice->selectionCriterias as $selectionCriteria) {
            DB::beginTransaction();
            try {
                $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
                Schema::connection('pgsql-chef')->create($table, function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->foreignId('subscription_id')->constrained();
                    $table->foreignId('offer_id')->constrained();
                    $table->integer('call_number');
                    $table->foreignId('migration_vacancy_map_id')->nullable()->constrained();
                    $table->unsignedBigInteger('distribution_vacancy_used_id')
                        ->comment('Distribuição de vagas usada, para descontar das disponíveis');
                    $table->unsignedBigInteger('distribution_vacancy_need_id')
                        ->comment('Distribuição necessária para comprovação do candidato');
                    $table->integer('call_position');
                    $table->boolean('is_wide_concurrency')->default(false);
                    $table->enum('status', [
                        'matriculado',
                        'pendente',
                        'não matriculado',
                        'pré cadastro'
                    ])->default('pendente');
                    $table->unique(['subscription_id', 'call_number']);
                    $table->foreign('distribution_vacancy_used_id')
                        ->references('id')
                        ->on('distribution_of_vacancies');
                    $table->foreign('distribution_vacancy_need_id')
                        ->references('id')
                        ->on('distribution_of_vacancies');
                });
                $notice->enrollment_call_table_created = true;
                $notice->save();
                DB::commit();
            } catch (QueryException $queryException) {
                Log::warning("A tabela $table já existe ou um outro problema ocorreu: {$queryException->getMessage()}", ['EnrollmentCall']);
                DB::rollBack();
            }
        }
    }

    private function createEnrollmentTable(Notice $notice)
    {
        $enrollmentTable = $notice->getEnrollmentProcessTableName();
        $docsTable = $notice->getEnrollmentProcessDocumentsTableName();
        try {
            Schema::connection('pgsql-chef')->create($enrollmentTable, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('subscription_id')->constrained();
                $table->integer('call_number');
                $table->unique(['subscription_id', 'call_number']);
                $table->text('feedback')->nullable();
                $table->timestamp('send_at')->nullable();
                $table->unsignedBigInteger('feedback_user_id')->nullable();
                $table->foreign('feedback_user_id')
                    ->references('id')
                    ->on('users');
            });
        } catch (QueryException $queryException) {
            Log::warning("A tabela $enrollmentTable já existe ou um outro problema ocorreu: {$queryException->getMessage()}", ['EnrollmentCall']);
        }
        try {
            Schema::connection('pgsql-chef')->create($docsTable, function (Blueprint $table) use ($enrollmentTable) {
                $table->bigIncrements('id');
                $table->uuid('uuid')->unique();
                $table->unsignedBigInteger('enrollment_process_id');
                $table->foreign('enrollment_process_id')
                    ->references('id')
                    ->on($enrollmentTable);
                $table->foreignId('document_type_id')->constrained();
                $table->string('document_type');
                $table->string('document_title');
                $table->string('url');
                $table->string('path');
                $table->string('extension');
                $table->string('mime_type');
                $table->text('feedback')->nullable();
                $table->unsignedBigInteger('feedback_user_id')->nullable();
                $table->foreign('feedback_user_id')
                    ->references('id')
                    ->on('users');
            });
        } catch (QueryException $queryException) {
            Log::warning("A tabela $enrollmentTable já existe ou um outro problema ocorreu: {$queryException->getMessage()}", ['EnrollmentCall']);
        }
        $this->grantPrivileges($notice);
    }

    private function createSchema(Notice $notice)
    {
        $schemaName = $notice->getNoticeSchemaName();
        $username = config('database.connections.pgsql.username');
        $csi_ro = env('DB_CSI_RO_USERNAME');
        $csi_rw = env('DB_CSI_RW_USERNAME');
        DB::connection('pgsql-chef')->unprepared("CREATE SCHEMA IF NOT EXISTS {$schemaName}");
        DB::connection('pgsql-chef')->unprepared("GRANT USAGE ON schema {$schemaName} to {$username}");
        DB::connection('pgsql-chef')->unprepared("GRANT USAGE ON schema {$schemaName} to {$csi_ro}");
        DB::connection('pgsql-chef')->unprepared("GRANT ALL ON SCHEMA {$schemaName} to {$csi_rw}");
    }

    private function grantPrivileges(Notice $notice)
    {
        $schemaName = $notice->getNoticeSchemaName();
        $username = config('database.connections.pgsql.username');
        $csi_rw = env('DB_CSI_RW_USERNAME');
        $csi_ro = env('DB_CSI_RO_USERNAME');
        DB::connection('pgsql-chef')
            ->unprepared("GRANT select,insert,update,delete ON all tables in schema {$schemaName} to {$username}");
        DB::connection('pgsql-chef')
            ->unprepared("GRANT select,insert,update,delete ON all tables in schema {$schemaName} to {$csi_rw}");
        DB::connection('pgsql-chef')
            ->unprepared("GRANT select ON all tables in schema {$schemaName} to {$csi_ro}");
        DB::connection('pgsql-chef')
            ->unprepared("GRANT all ON all sequences in schema {$schemaName} to {$username}");
        DB::connection('pgsql-chef')
            ->unprepared("GRANT all ON all sequences in schema {$schemaName} to {$csi_rw}");
    }

    public function makeNewCall(Notice $notice, $enrollment_start_date, $enrollment_end_date, $protected_affirmative_actions = 'N')
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $this->notice = $notice;
        $this->protectedAffirmativeActions = $protected_affirmative_actions;
        DB::listen(function ($query) {
            Log::info($query->sql);
            Log::info($query->bindings);
            Log::info($query->time);
        });
        DB::beginTransaction();
        try {
            $callCounting = $this->enrollmentCallRepository->countCallsByNotice($notice);
            $this->checkIfPendings($notice);
            // Sim, um monte de foreach aninhado e vai ficar assim!
            foreach ($notice->selectionCriterias as $selectionCriteria) {
                $callNumber = $callCounting[$selectionCriteria->id]['last_call_number'] + 1;
                Log::info("Começando a chamada $callNumber para {$selectionCriteria->description}");
                foreach ($notice->offers as $offer) {
                    Log::info("--- Oferta: {$offer->getString()}");
                    $this->subscriptionsInCall = [];
                    $this->vacancyTable = $this->mountVacanciesTableByOfferAndCriteria($offer, $selectionCriteria);
                    foreach ($this->vacancyTable['distributionVacancy'] as $distribution) {
                        $this->runCall(
                            $callNumber,
                            $offer,
                            $this->vacancyTable['distributionVacancyAvailable'][$distribution->id],
                            $distribution,
                            null
                        );
                    }
                    $this->vacancyTable = [];
                }
                if ($this->subscriptionsInCall) {
                    $notice->enrollmentSchedule()->updateOrCreate(
                        [
                            'call_number'           => $callNumber,
                            'selection_criteria_id' => $selectionCriteria->id
                        ],
                        [
                            'start_date'            => $enrollment_start_date,
                            'end_date'              => $enrollment_end_date,
                        ]
                    );
                }
            }
            DB::commit();
        } catch (Exception $exception) {
            Log::error($exception->getMessage(), ['EnrollmentCall', 'MakeNewCall']);
            Log::error($exception->getTraceAsString());
            DB::rollBack();
            $now = now();
            throw new Exception($exception->getMessage() . " | Linha " . $exception->getLine() . " | $now");
        }
    }

    protected function checkIfPendings(Notice $notice)
    {
        foreach ($notice->selectionCriterias as $selectionCriteria) {
            $count = DB::table($notice->getEnrollmentCallTableNameByCriteria($selectionCriteria))
                ->where('status', 'pendente')
                ->count() > 0;
            if ($count) throw new Exception(' - Existem inscrições com status pendente, chamada CANCELADA');
        }
    }

    private function runCall(
        int $callNumber,
        Offer $offer,
        int $numberOfVacancies,
        DistributionOfVacancies $distributionOfVacancy,
        ?MigrationVacancyMap $currentMigrationMap
    ) {
        if ($numberOfVacancies <= 0) return;
        Log::info("--------- Chamada da Distribuição: $distributionOfVacancy->id para $numberOfVacancies vagas");

        if ($currentMigrationMap) {
            $distributionForSearch = DistributionOfVacancies::byCriteria($distributionOfVacancy->selectionCriteria)
                ->where('offer_id', $offer->id)
                ->where('affirmative_action_id', $currentMigrationMap->affirmative_action_to_id)
                ->first() ?? $distributionOfVacancy;
            Log::info("Estou migrando $numberOfVacancies vagas de {$distributionOfVacancy->id} para {$distributionForSearch->id} ");
        } else {
            $distributionForSearch = $distributionOfVacancy;
        }

        $scoreTable = $offer->notice->getScoreTableNameForCriteriaId($distributionForSearch->selection_criteria_id);

        $this->callTableName = $offer->notice->getEnrollmentCallTableNameByCriteria($distributionForSearch->selectionCriteria);
        $elegible = DB::table("$scoreTable as score")
            ->select(
                'score.*',
                'call.call_number',
                'call.status',
                'call.is_wide_concurrency as call_is_wide',
                'call.distribution_vacancy_need_id',
                'call.distribution_vacancy_used_id',
                'users.name as user_name'
            )
            ->join('core.subscriptions', 'subscriptions.id', '=', 'score.subscription_id')
            ->join('core.users', 'users.id', '=', 'subscriptions.user_id')
            ->leftJoin(
                "{$this->callTableName} as call",
                "score.subscription_id",
                '=',
                'call.subscription_id'
            )
            ->where('score.offer_id', $offer->id)
            ->where('core.subscriptions.is_homologated', true)
            ->whereNull('core.subscriptions.elimination');


        if (!$distributionForSearch->affirmativeAction->is_wide_competition) {
            $elegible->where('score.distribution_of_vacancies_id', $distributionForSearch->id)
                ->whereNull('call.status');
        }

        $lessSubscriptionsThanVacancies = $this->vacancyTable['total_subscriptions'] <= $this->vacancyTable['available'];

        if ($distributionForSearch->affirmativeAction->is_wide_competition) {
            if ($this->protectedAffirmativeActions == 'Y' && $lessSubscriptionsThanVacancies == true && $callNumber == 1) {
                $elegible->where('score.distribution_of_vacancies_id', $distributionForSearch->id)
                ->whereNull('call.status');
            } else {
                $elegible->where(function ($q) use ($callNumber) {
                    $q->whereNull('call.status');
                    $q->orWhere(function ($q2) {
                        $q2->where('call.status', 'não matriculado');
                        $q2->where('call.is_wide_concurrency', false);
                    });

                    $q->orWhere(function ($q3) use ($callNumber) {
                        $q3->where('call.status', 'pendente');
                        $q3->where('call.call_number', $callNumber);
                        $q3->where('call.is_wide_concurrency', false);
                    });
                });
            }
            $elegible->whereRaw(
                "score.subscription_id not in
                (select older.subscription_id from {$this->callTableName} as older where 
                    older.status is not null and 
                    older.call_number < $callNumber and
                    older.is_wide_concurrency is true)"
            );
            $elegible->whereNotIn('score.subscription_id', $this->subscriptionsInCall[$distributionForSearch->id] ?? []);
        }

        if ($distributionForSearch->affirmativeAction->is_ppi) {
            $elegible->where('subscriptions.is_ppi_checked', true);
        }


        if ($lessSubscriptionsThanVacancies && $callNumber === 1 && $distributionOfVacancy->affirmativeAction->is_wide_competition) {
            if ($distributionOfVacancy->selectionCriteria->id > 2) {
                $elegible->orderBy('score.media', 'desc');
            } else {
                $elegible->orderBy('users.name', 'asc');
            }
            $numberOfVacancies = $this->vacancyTable['total_subscriptions'];
        } else {
            $elegible->orderBy('global_position', 'asc');
        }
        
        $elegible = $elegible->limit($numberOfVacancies)->get();

        $remnant = $numberOfVacancies - $elegible->count();

        Log::info("Achei $elegible e sobrará $remnant vagas", ['Enrolmment', 'Remmant']);
        $distributionForReorder = [];
        $migrationForWideCompetition = [];
        foreach ($elegible as $subscriptionScore) {
            $this->approve(
                $subscriptionScore,
                $callNumber,
                $distributionOfVacancy,
                $distributionForSearch,
                $currentMigrationMap
            );
            if ($currentMigrationMap && $distributionForSearch->affirmativeAction->is_wide_competition && $subscriptionScore->status == 'pendente') {
                $distributionForRedistribution = $subscriptionScore->distribution_vacancy_used_id;
                $toReorder = $subscriptionScore->distribution_vacancy_need_id;
                $distributionForReorder[$toReorder] = isset($distributionForReorder[$toReorder]) ? $distributionForReorder[$toReorder] + 1 : 1;
                $migrationForWideCompetition[$distributionForRedistribution] = isset($migrationForWideCompetition[$distributionForRedistribution]) ? $migrationForWideCompetition[$distributionForRedistribution] + 1 : 1;
            }
        }
        foreach ($migrationForWideCompetition as $key => $value) {
            $distribution = DistributionOfVacancies::find($key);
            $this->runCall($callNumber, $distribution->offer, $value, $distribution, null);
        }

        foreach ($distributionForReorder as $key => $value) {
            $distribution = DistributionOfVacancies::find($key);
            $this->reorderCall($distribution, $callNumber, $value);
        }

        $nextMigrationVacancyMap = $distributionOfVacancy->affirmativeAction
            ->migrationVacancyMap()
            ->where('order', '>', $currentMigrationMap->order ?? -1000)
            ->orderBy('order', 'asc')
            ->first();

        if ($remnant > 0 && $nextMigrationVacancyMap) {
            $this->runCall(
                $callNumber,
                $offer,
                $remnant,
                $distributionOfVacancy,
                $nextMigrationVacancyMap
            );
        }
    }

    private function reorderCall(DistributionOfVacancies $distributionOfVacancy, $callNumber, $numberOfMigratedFromWide)
    {
        DB::table($this->callTableName)
            ->where('call_number', $callNumber)
            ->where('distribution_vacancy_need_id', $distributionOfVacancy->id)
            ->update(
                ['call_position' => DB::raw("call_position - $numberOfMigratedFromWide")]
            );
    }

    private function mountVacanciesTableByOfferAndCriteria(Offer $offer, SelectionCriteria $selectionCriteria)
    {
        $table = [];
        $table['total'] = $offer->getTotalVacancies($selectionCriteria);
        $table['total_subscriptions'] = $offer->getTotalHomologatedSubscriptions($selectionCriteria, true);
        $table['used'] = $this->enrollmentCallRepository->countVacancyUsedByOfferAndSelectionCriteria(
            $offer,
            $selectionCriteria
        );
        $table['available'] = $table['total'] - $table['used'];
        $distributionVacancies = $offer->distributionVacancies()
            ->select('distribution_of_vacancies.*')
            ->byCriteria($selectionCriteria)
            ->orderByAffirmativeActionPriority()
            ->get();

        foreach ($distributionVacancies as $distribution) {
            $vacancies = $distribution->total_vacancies;
            $this->callOrderByDistribution[$distribution->id] = 0;
            $table['distributionVacancy'][$distribution->id] = $distribution;
            $table['distributionVacancyCallOrder'][$distribution->id] = 0;
            $table['distributionVacancyCount'][$distribution->id] = $vacancies;

            $table['distributionVacancyUsed'][$distribution->id] =
                $this->enrollmentCallRepository->countVacancyUsedByDistributionOfVacancy($distribution);

            $table['distributionVacancyAvailable'][$distribution->id] =
                $table['distributionVacancyCount'][$distribution->id] - $table['distributionVacancyUsed'][$distribution->id];
        }

        return $table;
    }

    private function approve(
        $subscriptionScore,
        $callNumber,
        DistributionOfVacancies $distributionOfVacancy,
        DistributionOfVacancies $distributionOfVacancyForEnroll,
        ?MigrationVacancyMap $migrationVacancyMap
    ) {
        $is_wide = (bool) $distributionOfVacancyForEnroll->affirmativeAction->is_wide_competition;
        $this->subscriptionsInCall[$distributionOfVacancyForEnroll->id][] = $subscriptionScore->subscription_id;
        DB::table($this->callTableName)
            ->updateOrInsert(
                [
                    'subscription_id' => $subscriptionScore->subscription_id,
                    'call_number'   => $callNumber,
                ],
                [
                    'subscription_id' => $subscriptionScore->subscription_id,
                    'call_number'   => $callNumber,
                    'migration_vacancy_map_id'  => $migrationVacancyMap->id ?? null,
                    'offer_id' => $distributionOfVacancyForEnroll->offer_id,
                    'distribution_vacancy_used_id' => $distributionOfVacancy->id,
                    'distribution_vacancy_need_id' => $distributionOfVacancyForEnroll->id,
                    'is_wide_concurrency' => $is_wide,
                    'call_position' => $this->vacancyTable['distributionVacancyCallOrder'][$distributionOfVacancyForEnroll->id] += 1,
                    'status' => 'pendente'
                ]
            );
    }
}
