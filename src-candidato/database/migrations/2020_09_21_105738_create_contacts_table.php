<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('street');
            $table->string('number',10)->nullable();
            $table->string('district');
            $table->string('zip_code',9);
            $table->string('phone_number',20);
            $table->boolean('has_whatsapp')->default(false);
            $table->boolean('has_telegram')->default(false);
            $table->string('alternative_phone_number',20)->nullable();
            $table->unsignedBigInteger('city_id');            
            
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->dropIfExists('contacts');
    }
}
