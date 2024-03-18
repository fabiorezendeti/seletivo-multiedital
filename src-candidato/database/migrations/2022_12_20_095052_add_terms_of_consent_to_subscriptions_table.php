<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTermsOfConsentToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->boolean('term_of_authorization_image_use')->nullable();
            $table->boolean('term_of_responsibility_for_damage_caused')->nullable();
            $table->boolean('term_consent_of_regulation_student_conduct')->nullable();
            $table->boolean('term_of_authorization_for_tours_and_trips')->nullable();
            $table->boolean('term_of_veracity_of_information_provided')->nullable();
            $table->boolean('term_of_consent_to_foreign_language_placement_test')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('term_of_authorization_image_use');
            $table->dropColumn('term_of_responsibility_for_damage_caused');
            $table->dropColumn('term_consent_of_regulation_student_conduct');
            $table->dropColumn('term_of_authorization_for_tours_and_trips');
            $table->dropColumn('term_of_veracity_of_information_provided');
            $table->dropColumn('term_of_consent_to_foreign_language_placement_test');
        });
    }
}
