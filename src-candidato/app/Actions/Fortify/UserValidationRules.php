<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Validation\Rule;

trait UserValidationRules {


    use PasswordValidationRules;

    protected $basicRules = [
        'name'          => ['required', 'string', 'max:255'],
        'email'         => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users', 'confirmed'],
        'cpf'           => ['required', 'string', 'cpf','formato_cpf', 'unique:users'],
        'rg'            => ['required', 'string', 'max:40'],
        'rg_emmitter'   => ['required', 'string', 'max:255'],
        'social_name'   => ['nullable','string', 'max:255'],
        'mother_name'   => ['required', 'string', 'max:255'],
        'birth_date'    => ['required','date_format:d/m/Y'],
        'is_foreign'    => ['required'],
        'nationality'   => ['required_if:is_foreign,1']
    ];

    protected $contactRules = [
        'street'        => ['required','max:255'],
        'number'        => ['required','max:10'],
        'district'      => ['required','max:255'],
        'zip_code'      => ['required','formato_cep','max:10'],
        'city'          => ['required'],
        'state'         => ['required'],
        'phone_number'  => ['required','celular_com_ddd'],
        'alternative_phone_number' => ['nullable','celular_com_ddd'],
        'complement'    => ['nullable','max:255']
    ];

    protected $basicMessages = [
        'name'          => 'Nome',
        'cpf'           => 'CPF',
        'rg'            => 'R.G.',
        'rg_emmitter'   => 'Emissor do RG',
        'social_name'   => 'Nome Social',
        'mother_name'   => 'Nome da Mãe',
        'password'      => 'Senha',
        'birth_date'    => 'Data de Nascimento',
        'is_foreign'    => 'Estrangeiro',
        'nationality'   => 'Nacionalidade',
        'pin'           => 'PIN'
    ];

    protected $contactMessages = [
        'street'        => 'Rua',
        'number'        => 'Número',
        'district'      => 'Bairro/Distrito',
        'zip_code'      => 'CEP',
        'city'          => 'Cidade',
        'state'         => 'Estado',
        'phone_number'  => 'Telefone (principal)',
        'alternative_phone_number'  => 'Telefone (alternativo)',
    ];

    public function getBasicRules()
    {
        return $this->basicRules;
    }


    public function getBasicMessage()
    {
        return $this->basicMessages;
    }

    public function getContactRules()
    {
        return $this->contactRules;
    }


    public function getContactMessage()
    {
        return $this->contactMessages;
    }

    public function getUserRegistrationValidationRules()
    {
        $rules = $this->getBasicRulesWithContact();
        if(env('LOGIN_UNICO_ENABLE')){
            $rules['pin'] = $this->passwordRules();
        }else{
            $rules['password'] = $this->passwordRules();
        }
        return $rules;
    }

    public function getBasicRulesWithContact()
    {
        return array_merge($this->basicRules,$this->contactRules);
    }

    public function getBasicMessageWithContact()
    {
        return array_merge($this->basicMessages, $this->contactMessages);
    }


}
