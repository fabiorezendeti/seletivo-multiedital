<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassificationOrderToAffirmativeActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('affirmative_actions', function (Blueprint $table) {
            $table->integer('classification_priority')->default(1);
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
            $table->dropColumn('classification_priority');
        });
    }
}
