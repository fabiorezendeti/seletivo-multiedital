<?php

namespace App\Http\LiveComponents\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\User\Role;
use App\Models\Organization\Campus;
use App\Models\User\Permission;
use Illuminate\Support\Facades\Auth;

class UserPermission extends Component
{

    public $uuid;
    public $role_id;
    public $campus_id;

    protected $rules = [        
        'role_id'   => ['required' , 'integer'],             
        'campus_id' => ['required_if:role_id,2'],
    ];

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $this->role_id = 1;   
        $this->campus_id = null;     
    }
    


    public function addPermission()
    {                   

        $this->campus_id = $this->campus_id != 0 ? $this->campus_id :  null;
        if ($this->role_id == 1) $this->campus_id = null ;

        $user = User::findOrFail($this->uuid);        
        $this->validate($this->rules,[
            'required_if'   => 'Campus é obrigatório para a permissão de CRA - Registro Acadêmico'
        ],[
            'role_id' => 'Permissão',
            'campus_id' => 'Campus'
        ]);
            

        $user->permissions()->updateOrCreate(
            [
                'role_id' => $this->role_id,
                'campus_id' => $this->campus_id,                
            ],[
            'role_id'   => $this->role_id,
            'campus_id' => $this->campus_id
        ]);

        $this->emit('saved');
    }

    public function revoke($permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $permission->delete();
    }

    public function render()
    {
        $roles = Role::all();
        $campuses = Campus::all();
        $user = User::findOrFail($this->uuid);
        return view('live-components.admin.user-permission',compact(
            'roles',
            'campuses',
            'user'
        ));
    }

}
