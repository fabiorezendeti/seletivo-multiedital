<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CreateKnowledgeAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('knowledge_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'KnowledgeAreaSeeder',
            '--force' => true,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knowledge_areas');
    }
}
