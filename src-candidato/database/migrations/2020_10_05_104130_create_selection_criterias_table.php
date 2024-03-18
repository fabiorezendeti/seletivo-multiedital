<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSelectionCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('selection_criterias', function (Blueprint $table) {
            $table->id();
            $table->string('description')->unique();
            $table->boolean('is_customizable')->default(false);
            $table->timestamps();
        });

        DB::table('selection_criterias')->insert(
            [
                ['id'=>1,'description'  => 'Sorteio','is_customizable'=>false],
                ['id'=>2,'description'  => 'Prova','is_customizable'=>false],
                ['id'=>3,'description'  => 'ENEM','is_customizable'=>true],
                ['id'=>4,'description' => 'Análise de Currículo','is_customizable'=>true]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->dropIfExists('selection_criterias');
    }
}
