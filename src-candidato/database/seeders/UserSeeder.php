<?php

namespace Database\Seeders;

use App\Models\Organization\Campus;
use App\Models\User;
use App\Models\User\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('slug','admin')->first();
        $roleCRA = Role::where('slug','academic-register')->first();
        $campus = Campus::first();

        User::factory(10)            
            ->create();

        $user = User::first();
        $user->cpf = '000.000.000-00';
        $user->password = Hash::make('12345678');
        $user->is_foreign = false;
        $user->permissions()->create([
            'role_id'   => $role->id,
        ]
        );
        $user->saveQuietly();
        
        $user = User::find(2);
        $user->cpf = '111.111.111-11';
        $user->password = Hash::make('12345678');
        $user->is_foreign = false;
        $user->permissions()->create([
            'role_id'   => $roleCRA->id,
            'campus_id' => $campus->id
        ]
        );
        $user->saveQuietly();
    }
}
