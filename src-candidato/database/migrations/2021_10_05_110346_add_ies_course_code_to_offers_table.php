<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIesCourseCodeToOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_campus_offers', function (Blueprint $table) {
            $table->bigInteger('sisu_course_code')->nullable()->unique()->comment('The Course Code, provided by SISU/MEC');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_campus_offers', function (Blueprint $table) {
            $table->dropColumn('sisu_course_code');
        });
    }
}
