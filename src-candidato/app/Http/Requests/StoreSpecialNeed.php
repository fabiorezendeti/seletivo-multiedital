<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreSpecialNeed extends FormRequest
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
                'description' => ['required', Rule::unique('special_needs')->ignore($this->special_need), 'max:250', 'string']            
        ];
    }

    public function attributes()
    {
        return [
            'description'   => 'descrição'
        ];
    }
}
