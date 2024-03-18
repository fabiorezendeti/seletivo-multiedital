<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->table('offers', function (Blueprint $table) {
            $table->foreign('notice_id')
                ->references('id')
                ->on('notices');

            $table->foreign('course_campus_offer_id')
                ->references('id')
                ->on('course_campus_offers');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->table('offers', function (Blueprint $table) {
            $table->dropForeign('notice_id');
            $table->dropForeign('course_campus_offer_id');
        });
    }
}
