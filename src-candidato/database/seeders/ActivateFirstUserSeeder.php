<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\User\Role;

/**
 * Essa seed irá configurar o primeiro usuário do banco de dados como administrador do sistema.
 * Ela só deve ser executada após a implantação do sistema e com um banco de dados vazio.
 * Antes de executá-la, certifique-se de ter cadastrado o usuário através da tela de cadastro.
 */
class ActivateFirstUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        if($user){
            $role = Role::where('slug','admin')->first();
            $user->permissions()->create([
                    'role_id'   => $role->id,
                ]
            );
            $user->save();
        }
    }
}
