<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class RenameResourceColumnToSubscriptionsTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    
        $schema = config('database.connections.pgsql.schema');
        DB::connection('pgsql-chef')->unprepared("ALTER TABLE $schema.subscriptions RENAME COLUMN preliminary_classification_resource to preliminary_classification_recourse");     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $schema = config('database.connections.pgsql.schema');
        DB::connection('pgsql-chef')->unprepared("ALTER TABLE $schema.subscriptions RENAME COLUMN preliminary_classification_recourse to preliminary_classification_resource");     
    }
}
