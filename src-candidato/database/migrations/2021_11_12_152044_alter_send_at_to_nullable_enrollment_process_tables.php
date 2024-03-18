<?php

use App\Models\Process\Notice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Output\ConsoleOutput;

class AlterSendAtToNullableEnrollmentProcessTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $notices = Notice::withTrashed()->get();
        $output = new ConsoleOutput();

        foreach ($notices as $notice) {
            try {
                $table = $notice->getEnrollmentProcessTableName();
                DB::connection('pgsql-chef')->unprepared("ALTER TABLE $table ALTER COLUMN \"send_at\" DROP NOT NULL;");
            } catch (Exception $exception) {
                //Log::error($exception->getMessage(), ['migration']);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $notices = Notice::withTrashed()->get();
        foreach ($notices as $notice) {
            try {
                $table = $notice->getEnrollmentProcessTableName();
                DB::connection('pgsql-chef')->unprepared("ALTER TABLE $table ALTER COLUMN \"send_at\" SET NOT NULL;");
            } catch (Exception $exception) {
                //Log::error($exception->getMessage(), ['migration']);
            }
        }
    }
}
