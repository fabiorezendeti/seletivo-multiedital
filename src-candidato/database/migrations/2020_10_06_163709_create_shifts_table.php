<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-chef')->create('course_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('description',100)->unique();            
        });

        DB::table('course_shifts')->insert(
            [
                ['id'=>1,'description'   => 'Integral' ],
                ['id'=>2,'description'   => 'Matutino' ],
                ['id'=>3,'description'   => 'Vespertino' ],
                ['id'=>4,'description'   => 'Noturno' ]
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
        Schema::connection('pgsql-chef')->dropIfExists('shifts');
    }
}
