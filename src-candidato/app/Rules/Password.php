<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Laravel\Fortify\Rules\Password as RulesPassword;

class Password extends RulesPassword
{
    protected $requireNumeric = true;
    protected $requireUppercase = true;
    public function message()
    {
        if ($this->message) {
            return $this->message;
        }

        if ($this->requireUppercase && ! $this->requireNumeric) {
            return __('O campo :attribute deve ter no mínimo '.$this->length.' caracteres and e conter uma letra maiúscula.');
        } elseif ($this->requireNumeric && ! $this->requireUppercase) {
            return __('O campo :attribute deve ter no mínimo '.$this->length.' caracteres e conter um número.');
        } elseif ($this->requireUppercase && $this->requireNumeric) {
            return __('O campo :attribute deve ter no mínimo '.$this->length.' caracteres e conter uma letra maiúscula e um número.');
        } else {
            return __('O campo :attribute deve ter '.$this->length.' caracteres.');
        }
    }

}
