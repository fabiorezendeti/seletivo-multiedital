<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_location_id')->constrained();
            $table->string('name',255);
            $table->integer('capacity');
            $table->boolean('for_special_needs')->default(false);
            $table->integer('priority');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_rooms');
    }
}
