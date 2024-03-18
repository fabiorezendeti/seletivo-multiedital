<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('campuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();            
            $table->unsignedBigInteger('city_id');            
            $table->timestamps();

            $table->foreign('city_id')
                ->references('id')
                ->on('cities');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->dropIfExists('campuses');
    }
}
