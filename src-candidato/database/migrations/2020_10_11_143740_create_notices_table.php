<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('description');
            $table->text('details');
            $table->string('link');
            $table->date('subscription_initial_date');
            $table->date('subscription_final_date');
            $table->date('classification_review_initial_date')->comment('Start period for request classification review');
            $table->date('classification_review_final_date')->comment('End period for request classification review');
            $table->boolean('has_fee');
            $table->decimal('registration_fee', 5, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->unsignedBigInteger('master_notice')->nullable();
            $table->timestamps();

            $table->foreign('master_notice')
                ->references('id')
                ->on('notices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-chef')->dropIfExists('notices');
    }
}
