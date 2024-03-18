<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class IFCParameterList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifc:parameters:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all parameters';

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
        $params = [];
        $list = DB::table('core.parameters')->select('name','value')->get();                
        foreach ($list as $item) {
            $params[] = [$item->name, $item->value];
        }
        $this->table(['Name','Value'],$params);
        return 0;
    }
}
