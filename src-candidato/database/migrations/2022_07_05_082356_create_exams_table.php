<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notice_id');
            $table->foreign("notice_id")->references("id")
                ->on("notices")->onDelete("cascade");
            $table->string('title',255);
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
        Schema::connection('pgsql-chef')->dropIfExists('exams');
    }
}
