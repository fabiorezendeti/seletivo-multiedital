<?php

namespace App\Console\Commands;

use Database\Seeders\DevProdObfuscateSeeder;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class IFCDevDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifc:dev:database {--generate-sql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reseta o banco de dados em modo de desenvolvimento';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (env('APP_ENV','production') === 'production') {
            $this->error('Você não pode rodar isso em produção');
            return 1;
        }

        $generateSql = $this->option('generate-sql');

        if ($generateSql) {
            $this->line('Ok, vamos gerar um arquivo para você, você precisa digitar a senha do usuário postgres');            
            $seed = base_path('database/scripts/seed.sql');
            exec("pg_dump -h pgsql -Uchef -d ingresso > $seed");
            $this->line("Confira o arquivo em $seed");
            return 0;
        }

        Artisan::call('db:seed',[
            'class' => DevProdObfuscateSeeder::class
        ]);

        return 0;
    }
}
