<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreModality extends FormRequest
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
                'description' => ['required', Rule::unique('modalities')->ignore($this->modality), 'max:150', 'string']            
        ];
    }

    public function attributes()
    {
        return [
            'description'   => 'descrição'
        ];
    }
}
