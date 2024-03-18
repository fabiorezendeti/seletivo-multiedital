<?php

use App\Models\Process\Notice;
use App\Models\Course\Modality;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModalityToNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->unsignedBigInteger('modality_id')->nullable();
            $table->foreign('modality_id')
                ->references('id')
                ->on('modalities');
        });

        try {
            $modality = Modality::find(1);

            if ($modality)
                Notice::where('description', 'ilike', '%Integr%')->update(
                    ['modality_id' => $modality->id]
                );

            $modality = Modality::find(2);

            if ($modality)
                Notice::where('description', 'ilike', '%Subsequent%')->update(
                    ['modality_id' => $modality->id]
                );

            $modality = Modality::find(3);
            
            if ($modality)
            Notice::where('description', 'ilike', '%Gradu%')->update(
                ['modality_id' => 3]
            );

            $modality = Modality::first();

            if ($modality)
            Notice::whereNull('modality_id')->update([
                'modality_id'  => 1
            ]);
            
        } catch (QueryException $exception) {
        }



        $schema = config('database.connections.pgsql.schema');
        DB::connection('pgsql-chef')->unprepared("ALTER TABLE core.notices ALTER COLUMN \"modality_id\" SET NOT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn('modality_id');
        });
    }
}
