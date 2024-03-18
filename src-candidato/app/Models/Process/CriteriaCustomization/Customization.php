<?php

namespace App\Models\Process\CriteriaCustomization;

use App\Models\Process\Notice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\Process\SelectionCriteria;
use Illuminate\Database\Schema\Blueprint;

class Customization
{
    private $notice;
    private $selectionCriteria;
    private $schemaName;
    private $scoreTable;
    private $view;
    private $rules = [];
    private $properties = [];


    public function __construct(Notice $notice, SelectionCriteria $selectionCriteria)
    {
        $this->notice = $notice;
        $this->selectionCriteria = $selectionCriteria;
        $this->schemaName = $notice->getNoticeSchemaName();
        $this->scoreTable = $notice->getScoreTableNameForCriteriaId($selectionCriteria->id);
    }

    public function toJson()
    {
        return json_encode([
            'view'      => $this->view,
            'rules'     => $this->rules,
            'properties'    => $this->properties,
        ]);
    }

    public function makeFromJson($json)
    {
        $object = json_decode($json);
        $this->view = $object->view;
        $this->rules = $object->rules;
        $this->properties = $object->properties;
    }

    private function makeDatabase(): void
    {
        $username = config('database.connections.pgsql.username');
        $csi_ro = env('DB_CSI_RO_USERNAME');
        $csi_rw = env('DB_CSI_RW_USERNAME');
        DB::connection('pgsql-chef')->unprepared("CREATE SCHEMA IF NOT EXISTS {$this->schemaName}");
        DB::connection('pgsql-chef')->unprepared("GRANT USAGE ON schema {$this->schemaName} to {$username}");
        DB::connection('pgsql-chef')->unprepared("GRANT USAGE ON schema {$this->schemaName} to {$csi_ro}");
        DB::connection('pgsql-chef')->unprepared("GRANT ALL ON SCHEMA {$this->schemaName} to {$csi_rw}");
        switch ($this->selectionCriteria->id) {
            case 2:
                $this->createProva();
                break;
            case 3:
                $this->createEnem();
                break;
            case 4:
                $this->createCurriculum();
                break;
            case 5:
                $this->createEnem(false);
                break;
        }
        DB::connection('pgsql-chef')->unprepared("GRANT select,insert,update,delete ON all tables in schema {$this->schemaName} to {$username}");
        DB::connection('pgsql-chef')->unprepared("GRANT all ON all sequences in schema {$this->schemaName} to {$username}");
        DB::connection('pgsql-chef')->unprepared("GRANT select,insert,update,delete ON all tables in schema {$this->schemaName} to {$csi_rw}");
        DB::connection('pgsql-chef')->unprepared("GRANT all ON all sequences in schema {$this->schemaName} to {$csi_rw}");
        DB::connection('pgsql-chef')->unprepared("GRANT select ON all tables in schema {$this->schemaName} to {$csi_ro}");
    }

    public function alterDatabaseForClassification(): void
    {
        switch ($this->selectionCriteria->id) {
            case 3:
                $this->updateEnemAndCurriculum();
                break;
            case 4:
                $this->updateEnemAndCurriculum();
                break;
            case 5:
                $this->updateEnemAndCurriculum();
                break;
        }
    }

    public function getSchemaName()
    {
        return $this->schemaName;
    }

    public function getScoreTable()
    {
        return "$this->schemaName.";
    }

    public function structureSave()
    {
        $this->makeDatabase();
        $this->copyViewTemplate(); // <- qual seria o objetivo dessa função?
    }

    private function copyViewTemplate(): void
    {
        switch ($this->selectionCriteria->id) {
            case 3:
                return;
            case 4:
                return;
        }
    }

    private function createEnem($documentNeed = true)
    {
        Schema::connection('pgsql-chef')->dropIfExists($this->scoreTable);
        Schema::connection('pgsql-chef')->create($this->scoreTable, function (Blueprint $table) use ($documentNeed) {
            $table->bigIncrements('id');
            $table->foreignId('subscription_id');
            $table->integer('ano_do_enem');
            $table->float('linguagens_codigos_e_tecnologias');
            $table->float('matematica_e_suas_tecnologias');
            $table->float('ciencias_humanas_e_suas_tecnologias');
            $table->float('ciencias_da_natureza_e_suas_tecnologias');
            $table->float('redacao');
            $table->float('media');
            $table->boolean("media_verificada")->nullable();
            if ($documentNeed) {
                $table->string('documento_comprovacao');
            } else {
                $table->string('documento_comprovacao')->nullable();
            }
            $table->timestamps();
        });
    }

    private function createProva($documentNeed = true)
    {
        Schema::connection('pgsql-chef')->dropIfExists($this->scoreTable);
        Schema::connection('pgsql-chef')->create($this->scoreTable, function (Blueprint $table) use ($documentNeed) {
            $table->bigIncrements('id');
            $table->foreignId('subscription_id')->unique();
            $table->float('linguagens_codigos_e_tecnologias');
            $table->float('matematica_e_suas_tecnologias');
            $table->float('ciencias_humanas_e_suas_tecnologias');
            $table->float('ciencias_da_natureza_e_suas_tecnologias');
            $table->float('nota');
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->integer('global_position')->nullable();
            $table->integer('distribution_of_vacancy_position')->nullable();
            $table->unsignedBigInteger('distribution_of_vacancies_id')->nullable();
            $table->boolean('is_eliminated')->default(false);
            $table->boolean('is_tied')->default(false);
            $table->timestamps();

            $table->foreign('distribution_of_vacancies_id')
                ->references('id')
                ->on('distribution_of_vacancies');

            $table->foreign('offer_id')
                ->references('id')
                ->on('offers');
        });
    }

    public function updateEnemAndCurriculum()
    {
        try {
            Schema::connection('pgsql-chef')->table($this->scoreTable, function (Blueprint $table) {
                $table->unsignedBigInteger('offer_id')->nullable();
                $table->integer('global_position')->nullable();
                $table->integer('distribution_of_vacancy_position')->nullable();
                $table->unsignedBigInteger('distribution_of_vacancies_id')->nullable();
                $table->boolean('is_eliminated')->default(false);
                $table->boolean('is_tied')->default(false);

                $table->foreign('distribution_of_vacancies_id')
                    ->references('id')
                    ->on('distribution_of_vacancies');

                $table->foreign('offer_id')
                    ->references('id')
                    ->on('offers');
            });
        } catch (QueryException $exception) {
            Log::warning("Um erro ocorreu ao alterar tabela de score {$this->scoreTable} . {$exception->getMessage()}", ['updateEnemCurriculumTables']);
        }
    }


    private function createCurriculum()
    {
        Schema::connection('pgsql-chef')->dropIfExists($this->scoreTable);
        Schema::connection('pgsql-chef')->create($this->scoreTable, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('subscription_id');
            $table->enum('modalidade', $this->notice->getModalitiesForCurriculumAnalisys()->pluck('title')->toArray());
            $table->float('linguagens_codigos_e_tecnologias')->nullable();
            $table->float('matematica_e_suas_tecnologias')->nullable();
            $table->float('ciencias_humanas_e_suas_tecnologias')->nullable();
            $table->float('ciencias_da_natureza_e_suas_tecnologias')->nullable();
            $table->string('documento_comprovacao');
            $table->float('media');
            $table->boolean("media_verificada")->nullable();
            $table->timestamps();
        });
    }
}
