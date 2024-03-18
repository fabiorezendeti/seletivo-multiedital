<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ApiController as ApiController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class AuthController extends ApiController
{
 //this method adds new users
 public function createAccount(Request $request)
 {
    $input = $request->all();
    $input['rg'] = '123456789';
    $input['rg_emmitter'] = 'SSP';
    $input['mother_name'] = 'SISTEMA';
    $input['birth_date'] = '01/01/1986';
     $validator = Validator::make($input, [
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required',
        'cpf' => 'required',
        'rg' => 'required',
        'rg_emmitter' => 'required',
        'mother_name' => 'required',
        'birth_date' => ['required','date_format:d/m/Y']
    ]);

    if($validator->fails()){
        return $this->sendError('Error validation', $validator->errors());       
    }

    
    $input['password'] = bcrypt($input['password']);
    $user = User::create($input);
    $success['token'] =  $user->createToken('Token-API')->plainTextToken;
    $success['name'] =  $user->name;

    return $this->sendResponse($success, 'User created successfully.',201);
 }
 //use this method to signin users
 public function signin(Request $request)
 {
     $attr = $request->validate([
         'email' => 'required|string',
         'password' => 'required|string|min:6'
     ]);

     if (!Auth::attempt($attr)) {
         return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 401);
     }
     $user = Auth::user();
     if ($user instanceof \App\Models\User) {
        $success['token'] =  $user->createToken('Token-API')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User signed in');

    } else {
        return $this->sendError('Não retornou um objeto User', $this->errors());
    }
 }

 // this method signs out users by removing tokens
 public function signout()
 {
    $user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $user->tokens()->delete();
        return [
            'message' => 'Tokens Revoked'
        ];
    } else {
        return $this->error('Erro ao realizar logout', 300);
    }     
 }

 /**
  * revoga e cria novo token para um usuário
  */
 public function newToken(User $user){
    $user->tokens()->delete();
    $success['token'] =  $user->createToken('Token-API')->plainTextToken;
    $success['name'] =  $user->name;
    return $this->sendResponse($success, 'User created successfully.',200);
 }
   
}