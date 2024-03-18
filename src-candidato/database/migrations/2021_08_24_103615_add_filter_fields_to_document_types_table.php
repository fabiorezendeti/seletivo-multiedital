<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilterFieldsToDocumentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_types', function (Blueprint $table) {
            $table->enum('age',['18+','<18'])->nullable(); // Maior que dezoito ou menor
            $table->enum('sex',['M','F'])->nullable(); // Masculino e Feminino
            $table->boolean('required')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_types', function (Blueprint $table) {
            $table->dropColumn('age');
            $table->dropColumn('sex');
            $table->dropColumn('required');
        });
    }
}
