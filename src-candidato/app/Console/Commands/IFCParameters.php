<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class IFCParameters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifc:parameter {name} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Or Update Custom Parameters';

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
        $name =  $this->argument('name');
        $value = $this->argument('value');
        try {
            DB::table('core.parameters')->updateOrInsert(
                [
                    'name' => $name
                ],
                [ 
                    'value' => $value
                ]
            );
        } catch (QueryException $exception) {
            $this->error('Um erro ocorreu: ' .  $exception->getMessage());
            return 1;
        } catch (Exception $exception) {
            $this->error('Um erro ocorreu: ' .  $exception->getMessage());
            return 1;
        }        
        return 0;
    }
}
