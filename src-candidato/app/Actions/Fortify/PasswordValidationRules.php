<?php

namespace App\Actions\Fortify;

use App\Rules\Password as RulesPassword;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        return ['required', 'string', new RulesPassword, 'confirmed'];
    }
}
