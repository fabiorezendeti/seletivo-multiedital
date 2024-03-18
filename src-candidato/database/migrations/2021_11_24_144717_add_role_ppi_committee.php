<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRolePpiCommittee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')
            ->where('id',3)
            ->update(
                ['name'=>'Membro da Comissão PPI']                        
            );        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')
            ->where('id',3)
            ->update(
                ['name'=>'Membro da Comissão']                        
            );       
    }
}