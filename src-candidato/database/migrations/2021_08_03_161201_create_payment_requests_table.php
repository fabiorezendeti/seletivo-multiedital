<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('payment_requests', function (Blueprint $table) {
            $table->id();            
            $table->text('request');
            $table->string('idPagamento')->nullable()->comment('campo obrigatório do pagtesouro');
            $table->string('situacao_codigo')->nullable();
            $table->foreignId('subscription_id')->constrained()->nullable();
            $table->text('proxima_url')->nullable();
            $table->softDeletes();
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
        Schema::connection('pgsql-chef')->dropIfExists('payment_requests');
    }
}
