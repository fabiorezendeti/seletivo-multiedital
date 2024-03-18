<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('answer_cards', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('notice_id');
            $table->foreign("notice_id")->references("id")
                ->on("notices")->onDelete("cascade");

            $table->unsignedBigInteger('exam_id');
            $table->foreign("exam_id")->references("id")
                ->on("exams")->onDelete("cascade");

            $table->unsignedBigInteger('subscription_id')->unique();
            $table->foreign("subscription_id")->references("id")
                ->on("subscriptions")->onDelete("cascade");

            $table->unsignedBigInteger('subscription_number')->unique();
            $table->foreign("subscription_number")->references("subscription_number")
                ->on("subscriptions")->onDelete("cascade");

            $table->boolean('is_absent')->nullable();
            $table->string('answers',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_cards');
    }
}
