<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;
    use UserValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, $this->getUserRegistrationValidationRules(),[],$this->getBasicMessageWithContact())->validate();

        return $this->createWithoutValidation($input);

    }

    public function createWithoutValidation(array $input)
    {
        $user = User::create([
            'name'          => $input['name'],
            'email'         => $input['email'],
            'cpf'           => $input['cpf'],
            'rg'            => $input['rg'],
            'rg_emmitter'   => $input['rg_emmitter'],
            'social_name'   => $input['social_name'],
            'mother_name'   => $input['mother_name'],
            'password'      => Hash::make($input['password']),
            'birth_date'   => Carbon::createFromFormat('d/m/Y',$input['birth_date'])->format('Y-m-d'),
            'is_foreign'    => $input['is_foreign'] ? true : false,
            'nationality'   => $input['is_foreign'] ? $input['nationality'] : null
        ]);

        $input['city_id'] = $input['city'];

        if ($input['street']) {
            $input['has_whatsapp'] = $input['has_whatsapp'] ?? false;
            $input['has_telegram'] = $input['has_telegram'] ?? false;
            $user->contact()->create($input);
        }

        return $user;
    }

}
