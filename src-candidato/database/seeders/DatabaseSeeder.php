<?php

namespace Database\Seeders;

use App\Models\Course\Course;
use App\Models\Process\Offer;
use App\Models\Process\SpecialNeed;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $schema = config('database.connections.pgsql.schema');
        $user =  config('database.connections.pgsql.username');
        $password = config('database.connections.pgsql.password');
        try {
        DB::connection('pgsql-chef')->unprepared("CREATE USER $user WITH PASSWORD '$password'");
        DB::connection('pgsql-chef')->unprepared("CREATE SCHEMA IF NOT EXISTS $schema");
        DB::connection('pgsql-chef')->unprepared("GRANT usage ON SCHEMA $schema to $user");
        DB::connection('pgsql-chef')->unprepared("grant select, insert, update, delete on all tables in schema  $schema to $user");
        DB::connection('pgsql-chef')->unprepared("grant all on all sequences in schema  $schema to $user");
        DB::connection('pgsql-chef')->unprepared("alter default privileges in schema $schema grant select,insert,update,delete on tables to $user");
        DB::connection('pgsql-chef')->unprepared("alter default privileges in schema $schema grant all on sequences to $user");
        } catch (QueryException $q) {

        }


        $this->call(
            [
                CampusSeeder::class,
                UserSeeder::class,
                AffirmativeActionsSeeder::class,
                ModalitySeeder::class,
                CourseSeeder::class,
                CampusOfferSeeder::class,
                NoticeSeeder::class,
                OfferSeeder::class,
                //ParametersSeeder::class,
                //SubscriptionSorteioSeeder::class,
                //SpecialNeed::factory(10)->create()
            ]
        );

    }
}
