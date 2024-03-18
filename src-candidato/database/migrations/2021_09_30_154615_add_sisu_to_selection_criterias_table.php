<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSisuToSelectionCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('selection_criterias')->insert([
            'id'=>5,
            'description' => 'SISU',
            'details'   => 'Inscrição no SISU (Sistema de Seleção Unificada)' ,
            'is_customizable'=>true
        ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('selection_criterias')->where('id','=',5)->delete();
    }
}
