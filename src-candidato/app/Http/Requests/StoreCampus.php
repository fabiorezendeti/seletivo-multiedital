<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreCampus extends FormRequest
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
            'name'          => ['required', 'string', 'max:255', Rule::unique('campuses')->ignore($this->campus)],
            'email'         => ['required', 'string', 'max:255'],
            'site'          => ['required', 'string', 'max:255'],
            'street'        => ['required', 'string', 'max:255'],
            'number'        => ['max:10'],
            'district'      => ['required', 'string', 'max:255'],
            'zip_code'      => ['required', 'formato_cep','max:10'],
            'phone_number'  => ['required', 'celular_com_ddd', 'max:255'],
            'city_id'       => ['required']
        ];
    }

     /**
     * Get the validation error message.
     *
     * @return string
     */
    public function attributes()
    {
        return [
            'name'          => 'Nome',
            'email'         => 'Email',
            'site'          => 'Site',
            'street'        => 'Rua/Avenida',
            'number'        => 'Número',
            'district'      => 'Bairro',
            'zip_code'      => 'CEP',
            'phone_number'  => 'Telefone',
            'city_id'       => 'Cidade'
        ];
    }
}
