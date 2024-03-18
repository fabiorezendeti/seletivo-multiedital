<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('states', function (Blueprint $table) {
            $table->id();
            $table->char('slug',2)->unique();
            $table->string('name');
            
        });

        Artisan::call('db:seed',[            
            '--class'=>'StateSeeder',
            '--force'=>true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->dropIfExists('states');
    }
}
