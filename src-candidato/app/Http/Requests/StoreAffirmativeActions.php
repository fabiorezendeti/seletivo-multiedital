<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreAffirmativeActions extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('isAdmin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'description'   => ['required'],
            'slug'          => ['required', Rule::unique('affirmative_actions')->ignore($this->affirmative_action), 'max:40', 'string'],
            'is_wide_competition' => ['nullable','boolean'],
            'is_ppi' => ['nullable','boolean'],
            'classification_priority'   => ['required','integer']
        ];
    }

    public function attributes()
    {
        return [
            'description'   => 'Descrição',
            'slug'          => 'Sigla',
            'is_wide_competition' => 'É Ampla Concorrência',
            'is_ppi' => 'É PPI'
        ];
    }
}
