<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMigrationVacancyMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('migration_vacancy_maps', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('affirmative_action_id');
            $table->unsignedBigInteger('affirmative_action_to_id');            
            $table->integer('order');
            $table->timestamps();


            $table->foreign('affirmative_action_id')
                ->references('id')
                ->on('affirmative_actions');

            $table->foreign('affirmative_action_to_id')
                ->references('id')
                ->on('affirmative_actions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('migration_vacancy_maps');
    }
}
