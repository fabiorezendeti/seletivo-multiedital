<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateExamRoomBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_room_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_location_id')->constrained();
            $table->foreignId('notice_id')->constrained();
            $table->string('name',255);
            $table->integer('capacity');
            $table->boolean('for_special_needs')->default(false);
            $table->integer('priority');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        /*
         * Apenas para alteração no modelo de ensalamento realizado em 2023
         * será inútil para novas instalações do Portal do Candidato
         *
         * MOVE OS DADOS DA TABELA ANTERIOR PARA A NOVA TABELA DE ENSALAMENTO
         */
        if(DB::table('exam_rooms')->count() > 0) {
            DB::table('exam_rooms')
                ->select('exam_rooms.*', 'subscriptions.notice_id')
                ->distinct()
                ->leftJoin('subscriptions', 'subscriptions.exam_room_id', '=', 'exam_rooms.id')
                ->orderBy('exam_rooms.id', 'ASC')
                ->whereNotNull('subscriptions.notice_id')
                ->chunk(1000, function ($dados) {
                    foreach ($dados as $dado) {
                        DB::table('exam_room_bookings')->insert([
                            'id' => $dado->id,
                            'exam_location_id' => $dado->exam_location_id,
                            'notice_id' => $dado->notice_id,
                            'name' => $dado->name,
                            'capacity' => $dado->capacity,
                            'for_special_needs' => $dado->for_special_needs,
                            'priority' => $dado->priority,
                            'active' => $dado->active,
                            'created_at' => $dado->created_at,
                            'updated_at' => $dado->updated_at
                        ]);
                    }
                });
            // Atualize o contador da sequência
            DB::statement("SELECT setval('exam_room_bookings_id_seq', (SELECT MAX(id) FROM exam_room_bookings) + 1)");

        }

        /**
         * RENOMEIA A COLUNA E ALTERA A REFERENCIA PARA O NOVO MODELO DE ENSALAMENTO
         */
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['exam_room_id']);
            $table->renameColumn('exam_room_id', 'exam_room_booking_id');
            $table->foreign('exam_room_booking_id')
                ->references('id')->on('exam_room_bookings');
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
            $table->dropForeign(['exam_room_booking_id']);
            $table->renameColumn('exam_room_booking_id', 'exam_room_id');
            $table->foreign('exam_room_id')
                ->references('id')->on('exam_rooms');
        });
        Schema::dropIfExists('exam_room_bookings');
    }
}
