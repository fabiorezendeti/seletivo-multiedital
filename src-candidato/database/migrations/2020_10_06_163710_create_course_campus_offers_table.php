<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseCampusOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('course_campus_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_shift_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('campus_id');
            $table->string('website');
            $table->timestamps();

            $table->foreign('course_shift_id')
                ->references('id')
                ->on('course_shifts');

            $table->unique(['course_id','campus_id','course_shift_id']);

            $table->foreign('campus_id')
                ->references('id')
                ->on('campuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->dropIfExists('course_campus_offers');
    }
}
