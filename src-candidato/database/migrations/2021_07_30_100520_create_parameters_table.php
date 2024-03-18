<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('value');
            $table->timestamps();
        });        

        Artisan::call('ifc:parameter',
        [
            'name'  => 'pagtesouro_token',
            'value' => 'eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiIxNTgxMjUifQ.gHyN_dV5d9taZhjNO30k1oNHGNdD3Crqpvru0mrwYLUKp6DWE87wssJov_nGsCSZsEG7CNpL4ZmR9Dru56Yngg3VsH9Hv2Kpsw0Vdc_9NWXEjPTQcA6Edbbxq2F4SyeF4vLIS03LkrvT7NGJxm8MkSUbKUTpdMvbIfkHCcpqMPUMbvqiUuawq6Kx5viBlnmSN1T3IDplNGQ50Zzx8GfUBO6qbICa0CLQsiBs_ccRn8bwVxHUuvScxQQyJWLt2X6Ccu4KZFiazVDPEPzaZssLUZSDd3BwnwIczYG0ajlLiI76ypUYurF69Fo24le2HJJWWKSQzX5sITK2VKHVxixRMw',            
        ]);

        Artisan::call('ifc:parameter',[
            'name'=>'pagtesouro_cod_servico',
            'value'=>'1561'
        ]);

        Artisan::call('ifc:parameter',[
            'name'  => 'pagtesouro_url_solicitacao_pagamento',
            'value' => 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento'
        ]);
        
        Artisan::call('ifc:parameter',[
            'name'  => 'pagtesouro_url_consulta_pagamento',
            'value' => 'https://valpagtesouro.tesouro.gov.br/api/gru/pagamentos'
        ]);


        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->dropIfExists('parameters');
    }
}
