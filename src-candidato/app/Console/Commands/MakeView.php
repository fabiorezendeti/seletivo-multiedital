<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class MakeView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifc:make-view {filepath : FilePath} {--type=single-table : Type | single-table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria uma view';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filepath = resource_path("views/{$this->argument('filepath')}") ;
        $type = $this->option('type');
                
        if (!File::exists(dirname($filepath))) {                        
            File::makeDirectory(dirname($filepath), 0777, true, true);
        }
        
        File::copy(resource_path('views/the-single.blade.php'), $filepath);
        return 0;
    }
}
