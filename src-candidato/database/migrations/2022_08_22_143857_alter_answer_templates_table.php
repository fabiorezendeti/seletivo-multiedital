<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAnswerTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('answer_templates', function (Blueprint $table) {
            $table->bigInteger('area_id')->nullable();
            $table->foreign("area_id")->references("id")
                ->on("knowledge_areas")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answer_templates', function (Blueprint $table) {
            $table->dropColumn('area_id');
        });
    }
}
