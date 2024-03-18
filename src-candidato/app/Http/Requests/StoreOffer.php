<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreOffer extends FormRequest
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
            'course_campus_offer_id' => ['required'],
            'total_vacancies'   => ['required','integer']
        ];
    }

    public function attributes()
    {
        return [
            'course_campus_offer_id' => "Curso",
            'total_vacancies'   => "Total de vagas"
        ];
    }
}
