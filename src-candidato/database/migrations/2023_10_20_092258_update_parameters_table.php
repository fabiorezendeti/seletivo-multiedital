<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class UpdateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('ifc:parameter',
        [
            'name'  => 'cnpj_instituicao',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'sigla_instituicao',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'nome_instituicao_completo',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'nome_instituicao_curto',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'endereco_reitoria',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'fone_instituicao',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'email_instituicao',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'cidades_campus',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'gru_codigo_favorecido',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'gru_gestao',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'gru_codigo_correlacao',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'gru_nome_favorecido',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'gru_codigo_recolhimento',
            'value' => '',            
        ]);

        Artisan::call('ifc:parameter',
        [
            'name'  => 'gru_nome_recolhimento',
            'value' => '',            
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            DB::table('parameters')->where('name','cnpj_instituicao')->delete();
            DB::table('parameters')->where('name','sigla_instituicao')->delete();
            DB::table('parameters')->where('name','nome_instituicao_completo')->delete();
            DB::table('parameters')->where('name','nome_instituicao_curto')->delete();
            DB::table('parameters')->where('name','endereco_reitoria')->delete();
            DB::table('parameters')->where('name','fone_instituicao')->delete();
            DB::table('parameters')->where('name','email_instituicao')->delete();
            DB::table('parameters')->where('name','cidades_campus')->delete();
            DB::table('parameters')->where('name','gru_codigo_favorecido')->delete();
            DB::table('parameters')->where('name','gru_gestao')->delete();
            DB::table('parameters')->where('name','gru_codigo_correlacao')->delete();
            DB::table('parameters')->where('name','gru_nome_favorecido')->delete();
            DB::table('parameters')->where('name','gru_codigo_recolhimento')->delete();
            DB::table('parameters')->where('name','gru_nome_recolhimento')->delete();
    }
}
