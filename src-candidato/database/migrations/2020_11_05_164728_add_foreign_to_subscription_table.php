<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->table('subscriptions', function (Blueprint $table) {
            $table->foreign('notice_id')
                ->references('id')
                ->on('notices');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('distribution_of_vacancies_id')
                ->references('id')
                ->on('distribution_of_vacancies');                              
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->table('subscription', function (Blueprint $table) {
            $table->dropForeign('notice_id');
            $table->dropForeign('user_id');
            $table->dropForeign('distribution_of_vacancies_id');                              
        });
    }
}
