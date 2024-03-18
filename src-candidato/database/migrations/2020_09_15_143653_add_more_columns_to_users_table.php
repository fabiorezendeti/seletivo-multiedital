<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique();       
            $table->string('cpf',14)->unique();
            $table->string('rg',40);
            $table->string('rg_emmitter');
            $table->string('social_name')->nullable();
            $table->string('mother_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->table('users', function (Blueprint $table) {
            $table->dropColumn([
                'uuid',
                'cpf',
                'rg',
                'rg_emmitter',
                'social_name',
                'mother_name'
            ]);
        });
    }
}
