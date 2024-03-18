<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsWideCompetitionToAffirmativeActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('affirmative_actions', function (Blueprint $table) {
            $table->boolean('is_wide_competition')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('affirmative_actions', function (Blueprint $table) {
            $table->dropColumn('is_wide_competition');
        });
    }
}
