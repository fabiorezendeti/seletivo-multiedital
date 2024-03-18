<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DevProdObfuscateSeeder extends Seeder
{

    public function run() 
    {
        
        if (env('APP_ENV','production') === 'production') {
            throw new Exception('Não use isso em produção');
            return 0;
        }
               
        $reset = base_path('database/scripts/reset.sql');
        $seed = base_path('database/scripts/seed.sql');

        exec("psql -h pgsql -Uchef -d postgres < $reset");        
        exec("psql -h pgsql -Uchef  -d ingresso < $seed");        

        Artisan::call('migrate',[
            '--database' => 'pgsql-chef'
        ]);
        
    }    

}