<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticeSelectionCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('notice_selection_criteria', function (Blueprint $table) {                     
            $table->unsignedBigInteger('notice_id');
            $table->unsignedBigInteger('selection_criteria_id');
            $table->jsonb('customization')->nullable();

            $table->unique(['notice_id','selection_criteria_id']);

            $table->foreign('notice_id')
                ->references('id')
                ->on('notices');

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
        Schema::connection('pgsql-chef')->dropIfExists('notice_selection_criteria');
    }
}
