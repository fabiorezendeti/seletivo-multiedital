<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\API\AuthController as AuthController;

class ApiSettingsController extends AuthController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg = null;
        $tokens = PersonalAccessToken::orderBy('tokenable_id')->paginate();
        
        return view('admin.api-settings.index',compact('tokens','msg'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.api-settings.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = $this->createAccount($request);
        $msg = null;
        //sucesso
        if($response->getStatusCode()==200){
            $msg = "Copie o token criado: {$response->getData()->data->token}";
            return view('admin.api-settings.show', compact('msg'));
        }else{
            $msg = "Erro ao criar o token: {$response->getData()->message}.";
            return redirect()->route('admin.api-settings.index')
                ->with('error',"O gabarito {$msg} não pode ser excluído.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($token)
    {
        $msg = null;
        $token = PersonalAccessToken::find($token);
        $user = User::find($token->tokenable_id);
        return view('admin.api-settings.show',compact('token','user','msg'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PersonalAccessToken $token)
    {
        return view('admin.api-settings.show',compact('token'));
    }

    /**
     * Renova token de um usuário
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user_id = $request->get('user_id');
        $user = User::find($user_id);
        $response = $this->newToken($user);

        if($response->getStatusCode()==200){
            $token = PersonalAccessToken::where('tokenable_id',$user->id);
            $msg = "Copie o novo token: {$response->getData()->data->token}";
            return view('admin.api-settings.show', compact('token','msg'));
        }else{
            $msg = "Erro ao criar o token: {$response->getData()->message}.";
            return redirect()->route('admin.api-settings.index')
                ->with('error',"O gabarito {$msg} não pode ser excluído.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($token)
    {
        $token = PersonalAccessToken::find($token);
        $user = User::find($token->tokenable_id);
        $user->tokens()->where('id', $token->id)->delete();
        return redirect()->route('admin.api-settings.index')
                ->with('success',"O token do usuário {$user->name} foi revogado.");
    }
}
