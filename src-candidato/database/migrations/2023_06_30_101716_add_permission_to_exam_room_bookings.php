<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class AddPermissionToExamRoomBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adicione a permissão usando a query 'GRANT'
        DB::connection('pgsql-chef')->unprepared("GRANT SELECT ON exam_room_bookings TO csi_ro");
        DB::connection('pgsql-chef')->unprepared("GRANT select,insert,update,delete ON exam_room_bookings to csi_rw");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverta a alteração removendo a permissão
        DB::connection('pgsql-chef')->unprepared("REVOKE SELECT ON exam_room_bookings FROM csi_ro");
        DB::connection('pgsql-chef')->unprepared("REVOKE select,insert,update,delete ON exam_room_bookings to csi_rw");

    }
}
