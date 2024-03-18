<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug',20)->unique();
            $table->boolean('global_scope')->default(false);
            $table->timestamps();
        });

        DB::table('roles')->insert([            
                ['id'=>1,'name'=>'Administrador','slug'=>'admin','global_scope'=>true],                
        ]);

        DB::table('roles')->insert([            
            ['id'=>2,'name'=>'Registro Acadêmico - Apoio','slug'=>'academic-register'],
        ]);

        DB::table('roles')->insert([            
            ['id'=>3,'name'=>'Membro da Comissão','slug'=>'committee'],                
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->dropIfExists('roles');
    }
}
