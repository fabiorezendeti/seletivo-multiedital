<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules(),
        ],[],[
            'password'  => 'senha',
            'current_password'  => 'senha atual'
        ])->after(function ($validator) use ($user, $input) {
            if (! Hash::check($input['current_password'], $user->password)) {
                $validator->errors()->add('current_password', env('LOGIN_UNICO_ENABLE')? 'O PIN informado não corresponde com o PIN atual' : 'A senha informada não corresponde com sua senha atual');
            }
        })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
