<?php

use App\Models\Process\AffirmativeAction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAffirmativeActionsPpiFromAffirmativeActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AffirmativeAction::where('slug','like','%PPI%')->update(
            [
                'is_ppi'    => true
            ]
        );        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        AffirmativeAction::where('slug','like','%PPI%')->update(
            [
                'is_ppi'    => false
            ]
        );        
    }
}
