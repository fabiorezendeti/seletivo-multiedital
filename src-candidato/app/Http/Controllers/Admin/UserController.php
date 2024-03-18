<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Audit\JustifyUpdates;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $users = User::where('name','ilike',"%$search%")->orWhere('email','ilike',"%$search%")->orWhere('cpf',"$search")->paginate();
        return view('admin.users.index',compact('users'));
    }        

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uid, UpdateUserProfileInformation $updateUserProfileInformation)
    {        
        $user = User::findOrFail($uid);        
        $user = $updateUserProfileInformation->updateBasicData($user,$request->all());    
        JustifyUpdates::create($user);
        return redirect()->route('admin.users.index')
            ->with('success',"O usuário foi {$user->cpf} atualizado com sucesso");
    }


    public function destroy(User $user)
    {
        $this->authorize('deleteUser',$user);
        $cpf = $user->cpf;
        $userArray = $user->toArray();
        $user->delete();
        JustifyUpdates::createFromArray($userArray);
        return redirect()->route('admin.users.index')
            ->with('success',"O usuário foi $cpf removido com sucesso");
    }
   
}
