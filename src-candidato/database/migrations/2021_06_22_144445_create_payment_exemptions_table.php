<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentExemptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('payment_exemptions', function (Blueprint $table) {
            $table->id();
            $table->string('document_id_front');
            $table->string('document_id_back');
            $table->string('document_form');
            $table->boolean('is_accepted')->nullable();
            $table->string('rejected_reason')->nullable();
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
        Schema::connection('pgsql-chef')->dropIfExists('payment_exemptions');
    }
}
