<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffirmativeActionDocumentTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affirmative_action_document_type', function (Blueprint $table) {
            $table->unsignedBigInteger('affirmative_action_id');
            $table->unsignedBigInteger('document_type_id');            

            $table->unique(['affirmative_action_id','document_type_id']);

            $table->foreign('affirmative_action_id')
                ->references('id')
                ->on('affirmative_actions');

            $table->foreign('document_type_id')
                ->references('id')
                ->on('document_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affirmative_action_document_type');
    }
}
