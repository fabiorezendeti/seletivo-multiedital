<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentExemption extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $uploadMaxSize = (int) ini_get("upload_max_filesize") * 1024 ;
        return [
            'document_id_front' => ['required', 'mimetypes:application/pdf,image/*', "max:$uploadMaxSize"],
            'document_id_back' => ['required', 'mimetypes:application/pdf,image/*', "max:$uploadMaxSize"],
            'document_form' => ['required', 'mimetypes:application/pdf,image/*', "max:$uploadMaxSize"]
        ];
    }

    public function attributes()
    {
        return [
            'document_id_front' => 'Documento de RG - Frente',
            'document_id_back' => 'Documento de RG - Verso',
            'document_form' => 'Formuláro de Requisição de Isenção',
        ];
    }
}
