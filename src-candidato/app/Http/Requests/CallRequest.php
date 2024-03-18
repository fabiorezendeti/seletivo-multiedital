<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CallRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('isAdmin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'enrollment_start_date'     => ['required', 'date'],
            'enrollment_end_date'       => ['required', 'date','after:enrollment_start_date'],
        ];
    }

    public function attributes()
    {
        return [
            'enrollment_start_date'     => 'Primeiro dia de matrículas',
            'enrollment_end_date'       => 'Último dia de matrículas',
        ];
    }

}
