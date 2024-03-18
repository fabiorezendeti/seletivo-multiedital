<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameIsHomologatedColumnToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    
        $schema = config('database.connections.pgsql.schema');
        DB::connection('pgsql-chef')->unprepared("ALTER TABLE $schema.subscriptions RENAME COLUMN \"isHomologated\" to is_homologated");     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $schema = config('database.connections.pgsql.schema');
        DB::connection('pgsql-chef')->unprepared("ALTER TABLE $schema.subscriptions RENAME COLUMN is_homologated to \"isHomologated\"");     
    }
}
