<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJustifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('justifies', function (Blueprint $table) {
            $table->id();
            $table->text('justify');            
            $table->jsonb('data');
            $table->unsignedBigInteger('author_id');
            $table->string('uri');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->dropIfExists('justifies');
    }
}
