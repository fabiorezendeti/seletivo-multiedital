<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToDistributionOfVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->table('distribution_of_vacancies', function (Blueprint $table) {
            $table->foreign('offer_id')
                ->references('id')
                ->on('offers');

            $table->foreign('affirmative_action_id')
                ->references('id')
                ->on('affirmative_actions');

            $table->foreign('selection_criteria_id')
                ->references('id')
                ->on('selection_criterias');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->table('distribution_of_vacancies', function (Blueprint $table) {
            $table->dropForeign('offer_id');
            $table->dropForeign('affirmative_action_id');
            $table->dropForeign('selection_criteria_id');
        });
    }
}
