<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreliminaryClassificationResourceToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->table('subscriptions', function (Blueprint $table) {
            $table->json('preliminary_classification_resource')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('preliminary_classification_resource');
        });
    }
}
