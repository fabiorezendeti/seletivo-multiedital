<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributionOfVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('distribution_of_vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id');
            $table->foreignId('affirmative_action_id');
            $table->foreignId('selection_criteria_id');
            $table->smallInteger('total_vacancies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->dropIfExists('distribution_of_vacancies');
    }
}
