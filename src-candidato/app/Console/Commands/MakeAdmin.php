<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\User\Role;
use Exception;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ifc:makeadmin {cpf}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give a specific user admin access';

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
        $role = Role::where('slug','admin')->first();
        $cpf = $this->argument('cpf');
        if (!$cpf) {
            $this->error('O usuário não existe!');
            return 1;
        }
        try {
            $user = User::where('cpf',$cpf)->first();
            $user->permissions()->create([
                'role_id'   => $role->id,
            ]);        
            $this->line('O usuário agora é admin');            
            return 0;
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
            return 1;
        }        
    }
}
