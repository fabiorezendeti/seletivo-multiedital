<?php

use App\Models\Process\AffirmativeAction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffirmativeActionModality extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affirmative_action_modality', function (Blueprint $table) {
            $table->unsignedBigInteger('affirmative_action_id');                
            $table->unsignedBigInteger('modality_id');
            $table->primary(['affirmative_action_id','modality_id']);

            $table->foreign('affirmative_action_id')
                ->references('id')
                ->on('affirmative_actions');

            $table->foreign('modality_id')
                ->references('id')
                ->on('modalities');
        });
        
        $affirmativeActions = AffirmativeAction::where('created_at','<=','2020-12-14 00:00:00')->get();
        foreach ($affirmativeActions as $affirmativeAction) {
            $affirmativeAction->modalities()->sync([1,2]);
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affirmative_action_modality');
    }
}
