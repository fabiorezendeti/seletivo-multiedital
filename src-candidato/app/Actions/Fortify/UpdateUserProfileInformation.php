<?php

namespace App\Actions\Fortify;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    use UserValidationRules;
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {        
        $this->updateBasicData($user,$input);        
    }


    public function updateBasicData($user,array $input)
    {                        
        $basicRules = $this->getBasicRules();          
        if ($user->cannot('updateCPF',$user)) {            
            $basicRules['cpf'] = ['nullable'];
        }
        if ($user->can('updateCPF',$user) && ($user->cpf == $input['cpf'] )) {            
            $basicRules['cpf'] = [
                'required', 
                'string', 
                'cpf',
                'formato_cpf', 
                Rule::unique('users')->ignore($user->id)];
        }
        if ($user->email == $input['email']) {
            $basicRules['email'] = [
                'required', 
                'email', 
                'max:255',
                'confirmed', 
                Rule::unique('users')->ignore($user->id)];
        }

        Validator::make($input,$basicRules,[],$this->getBasicMessage())->validate();                        
        $user->forceFill([
            'name'  => $input['name'],
            'social_name'   => $input['social_name'],
            'email'     => $input['email'],
            'cpf'       => ($user->can('updateCPF',$user)) ? $input['cpf'] : $user->cpf,
            'rg'        => $input['rg'],
            'rg_emmitter'    => $input['rg_emmitter'],
            'mother_name'   => $input['mother_name'],
            'birth_date'   => Carbon::createFromFormat('d/m/Y',$input['birth_date'])->format('Y-m-d'),
            'is_foreign'    => $input['is_foreign'] == 1 ? true : false,
            'nationality'   => $input['is_foreign'] ==  1 ? $input['nationality'] : null
        ]);
        $user->save();
        return $user;
    }

    public function updateContactData($user, array $input)
    {    
        Validator::make($input,$this->getContactRules(),[],$this->getContactMessage())->validate(); 
                
        $user->contact()
            ->updateOrCreate(
                [ 'user_id'=> $user->id],
                $input
            );
    }

}
