<?php

namespace App\Http\Requests;

use App\Models\Process\Notice;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreNotice extends FormRequest
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
        $criterias = ['nullable'];
        $displayExamRoomRules = ['nullable', 'date'];
        if(Auth::user()->can('updateCriteria',$this->notice) || !$this->notice) {
            $criterias = ['required','array'];
            if (in_array(2, $this->selection_criteria ?? []))
                $displayExamRoomRules = ['required', 'date'];
        }
        return [
            'number'                        => ['required', Rule::unique('notices')->ignore($this->notice), 'regex:/^[0-9]{2,3}+\/+[0-9]{4}$/'],
            'description'                   => ['required', 'string', 'max:255'],
            'details'                       => ['required', 'string'],
            'link'                          => ['required', 'url', 'string', 'max:200'],
            'subscription_initial_date'     => ['required', 'date'],
            'subscription_final_date'       => ['required', 'date','after:subscription_initial_date'],
            'modality_id'                   => ['required'],
            'classification_review_initial_date'   => ['required', 'date'],
            'classification_review_final_date'     => ['required', 'date'],
            'registration_fee'              => ['nullable','required_with:payment_date,', 'string', 'max:40'],
            'closed_at'                     =>  (!$this->closed_at) ? ['nullable','date'] : ['nullable','date','after:subscription_final_date'],
            'payment_date'                  => ['nullable','required_unless:registration_fee,"0,00"', 'date'],
            'selection_criteria'            => $criterias,
            'gru_config.competencia'        => ['required','regex:/^[0-9]{2,3}+\/+[0-9]{4}$/'],
            'pagtesouro_activated'          => ['nullable'],
            'display_exam_room_date'        => $displayExamRoomRules
            // 'display_exam_room_date'        => (in_array(2, $this->selection_criteria)) ? ['required', 'date'] : ['nullable', 'date']
        ];
    }

    public function attributes()
    {
        return [
            'number'                        => 'Número',
            'description'                   => 'Descrição',
            'details'                       => 'Detalhes',
            'modality_id'                   => 'Modalidade',
            'link'                          => 'Link de Publicação',
            'subscription_initial_date'     => 'Data Inicial para Inscrição',
            'subscription_final_date'       => 'Data Final para Inscrição',
            'classification_review_initial_date'   => 'Data Inicial para Recurso de Classificação',
            'classification_review_final_date'     => 'Data Final para Recurso de Classificação',
            'has_fee'                       => 'Possui taxa de inscrição?',
            'registration_fee'              => 'Taxa de Inscrição',
            'payment_date'                  => 'Data de Pagamento',
            'selection_criteria'            => 'Critério de Seleção',
            'closed_at'                     => 'Encerrar em',
            'pagtesouro_activated'          => 'Pagamento via PagTesouro',
            'display_exam_room_date'        => 'Data para disponibilização do local de prova'
        ];
    }

    public function messages()
    {
        return [
            'number.regex'                 => 'O formato do edital deve ser número/ano. Ex: 01/2020',
            'registration_fee.required_if' => 'A taxa de inscrição é obrigatória quando possui taxa de inscrição é SIM',
            'payment_date.required_if'     => 'A data de pagamento é obrigatória quando possui taxa de inscrição é SIM',
            'display_exam_room_date.required'  => 'A data para disponibilização do local de prova é obrigatória quando o critério de seleção contém prova',
        ];
    }
}
