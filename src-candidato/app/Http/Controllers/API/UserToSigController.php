<?php

namespace App\Http\Controllers\API;

use App\Models\UserToSig;
use App\Http\Resources\UserToSig as UserToSigResource;
use App\Http\Controllers\API\ApiController as ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserToSigController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($cpf)
    {

        //$user = User::where('cpf',$cpf)->firstOrFail();
        $user = DB::table('users')->select(
            'users.*', 'contacts.*', 'cities.name as city','states.slug as state'
        )
        ->join('contacts', 'contacts.user_id', '=', 'users.id')
        ->join('cities', 'cities.id', '=', 'contacts.city_id')
        ->join('states', 'states.id', '=', 'cities.state_id')
        ->where('cpf',$cpf)->first();
        
        if (is_null($user)) {
            return $this->sendError('CPF não consta na base de dados.');
        }
        $userToSig = new UserToSig();
        //fazer manipulação para pegar dados da contacts e montar o objeto do model UserToSig
        $userToSig->cpf = preg_replace("/\.|\-/", '', $user->cpf);
        $userToSig->nome_oficial = $user->name;
        $userToSig->nome_social = $user->social_name;
        $userToSig->email = $user->email;
        $userToSig->nome_mae = $user->mother_name;
        $userToSig->nome_pai = null;
        $userToSig->genero_oficial = $user->sex ? $user->sex : null;
        $userToSig->genero_social = null;
        $userToSig->data_nascimento = $user->birth_date;
        $userToSig->estado_civil = null;
        $userToSig->cor_raca = null;
        $userToSig->tipo_escola_ensino_medio = null;
        $userToSig->nome_escola_ensino_medio = null;
        $userToSig->ano_conclusao = null;
        $userToSig->tipo_sanguineo = null;
        $userToSig->rg_numero = $user->rg;
        $userToSig->rg_orgao_expedidor = $user->rg_emmitter;
        $userToSig->rg_data_expedicao = null;
        $userToSig->titulo_eleitor_numero = null;
        $userToSig->titulo_eleitor_zona = null;
        $userToSig->titulo_eleitor_sessao = null;
        $userToSig->titulo_eleitor_uf = null;
        $userToSig->titulo_eleitor_data_expedicao = null;
        $userToSig->endereco_cep = $user->zip_code;
        $userToSig->endereco_logradouro_tipo = 1;
        $userToSig->endereco_logradouro_nome = $user->street;
        $userToSig->endereco_numero = $user->number;
        $userToSig->endereco_complemento = $user->complement;
        $userToSig->endereco_bairro = $user->district;
        $userToSig->endereco_cidade = $user->city;
        $userToSig->endereco_estado = $user->state;
        $userToSig->telefone_fixo = $user->alternative_phone_number ?? $user->phone_number;
        $userToSig->telefone_celular = $user->phone_number;
        
        return $this->sendResponse(new UserToSigResource($userToSig), 'CPF localizado.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
