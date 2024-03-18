<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscription extends FormRequest
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
        if ($this->selection_criteria == 3) {
            return [
                'selection_criteria' => ['required','integer'],
                'distribution_of_vacancies' => ['required','integer'],
                'criteria_3_ano_do_enem'   => ['required', 'integer', 'between:2017,2020'],
                'criteria_3_linguagens_codigos_e_tecnologias'   => ['required', 'numeric','min:0', 'max:1000'],
                'criteria_3_matematica_e_suas_tecnologias' => ['required', 'numeric','min:0', 'max:1000'],
                'criteria_3_ciencias_humanas_e_suas_tecnologias' => ['required', 'numeric','min:0', 'max:1000'],
                'criteria_3_ciencias_da_natureza_e_suas_tecnologias' => ['required', 'numeric','min:0', 'max:1000'],
                'criteria_3_redacao' => ['required', 'numeric','min:0', 'max:1000'],
                'criteria_3_media' => ['required', 'numeric','min:0', 'max:1000'],
                'check_affirmative_action' => ['required','accepted']
                
            ];
        }
        if ($this->selection_criteria == 4 && $this->criteria_4_modalidade > 1) {                 
            $valorMaximo = ($this->criteria_4_modalidade == 2) ? 'max:1000' : 'max:180';      
            return [
                'selection_criteria' => ['required','integer'],
                'distribution_of_vacancies' => ['required','integer'],
                'criteria_4_modalidade' => ['required','integer'],
                'criteria_4_linguagens_codigos_e_tecnologias'   => ['required', 'numeric','min:0', $valorMaximo],
                'criteria_4_matematica_e_suas_tecnologias' => ['required',  'numeric','min:0', $valorMaximo],
                'criteria_4_ciencias_humanas_e_suas_tecnologias' => ['required',  'numeric', 'min:0', $valorMaximo],
                'criteria_4_ciencias_da_natureza_e_suas_tecnologias' => ['required',  'numeric','min:0', $valorMaximo],
                'criteria_4_media_certificacao' => ['required', 'numeric', 'min:0', 'max:10'],                
                'check_affirmative_action' => ['required','accepted']
            ];
        }
        if ($this->selection_criteria == 4 && $this->criteria_4_modalidade < 1) {
            return [
                'selection_criteria' => ['required','integer'],
                'distribution_of_vacancies' => ['required','integer'],
                'criteria_4_media_regular' => ['required','numeric','min:0', 'max:10'],                
                'check_affirmative_action' => ['required','accepted']
            ];
        }
        return [
            'selection_criteria' => ['required','integer'],
            'distribution_of_vacancies' => ['required','integer'],
            'check_affirmative_action' => ['required','accepted']
        ];
    }

    public function attributes()
    {
        return [
            'selection_criteria' => 'Critério de Seleção',
            'distribution_of_vacancies' => 'Ação Afirmativa',
            'documento_comprovacao' => 'Documento de comprovação',
            'criteria_3_ano_do_enem'   => 'Ano do ENEM',
            'criteria_3_linguagens_codigos_e_tecnologias'   => 'Nota de Linguagens, códigos e tecnologias',
            'criteria_3_matematica_e_suas_tecnologias' => 'Nota de Matemática e suas tecnologias',
            'criteria_3_ciencias_humanas_e_suas_tecnologias' => 'Nota de Ciências humanas e suas tecnologias',
            'criteria_3_ciencias_da_natureza_e_suas_tecnologias' => 'Nota de Ciências da natureza e suas tecnologias',
            'criteria_3_media' => 'Média',
            'criteria_4_modalidade' => 'Modalidade',
            'criteria_4_linguagens_codigos_e_tecnologias'   => 'Nota de Linguagens, códigos e tecnologias',
            'criteria_4_matematica_e_suas_tecnologias' => 'Nota de Matemática e suas tecnologias',
            'criteria_4_ciencias_humanas_e_suas_tecnologias' => 'Nota de Ciências humanas e suas tecnologias',
            'criteria_4_ciencias_da_natureza_e_suas_tecnologias' => 'Nota de Ciências da natureza e suas tecnologias',
            'criteria_4_media_certificacao' => 'Média',
            'criteria_4_media_regular' => 'Média',
            'check_affirmative_action'  => 'Declaro estar ciente ....'
        ];
    }
}
