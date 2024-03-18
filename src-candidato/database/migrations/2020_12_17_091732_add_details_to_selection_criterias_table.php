<?php

use App\Models\Process\SelectionCriteria;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDetailsToSelectionCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selection_criterias', function (Blueprint $table) {
            $table->string('details')->default('');
        });

        SelectionCriteria::find(1)->update([
            'details' => 'Sorteio'
        ]);

        SelectionCriteria::find(2)->update([
            'details' => 'Prova'
        ]);

        SelectionCriteria::find(3)->update([
            'details' => 'Nota Geral do ENEM'
        ]);

        SelectionCriteria::find(4)->update([
            'details' => 'Média Geral de Conclusão do Ensino Médio'
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selection_criterias', function (Blueprint $table) {
            $table->dropColumn('details');
        });
    }
}