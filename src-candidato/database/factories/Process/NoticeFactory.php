<?php

namespace Database\Factories\Process;

use App\Models\Course\Modality;
use App\Models\Process\Notice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Repository\ParametersRepository;

class NoticeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */


    public function definition()
    {
        $parameters = new ParametersRepository();
        $numero = random_int(10,400);
        $ano = Carbon::now()->get('year');
        $notice = new Notice();
        return [
            'number' => $numero.'/'.Carbon::now()->addYear($numero)->get('year'),
            'modality_id'  => Modality::inRandomOrder()->first()->id,
            'description' => 'Edital numero '.$numero.' do ano de '.$ano.', seeder',
            'details' => 'Edital gerado pelo seeder, este campo é somente dealhes.',
            'link' => 'http://ifc.edu.br/',
            'subscription_initial_date' => '2020-10-01',
            'subscription_final_date' => '2020-10-30',
            'classification_review_initial_date' => '2020-11-02',
            'classification_review_final_date' => '2020-11-30',
            'has_fee' => "0",
            'registration_fee' => "10.0",
            'payment_date' => '2020-10-30',
            'gru_config'   => [
                'codigo_favorecido'   => $parameters->getValueByName('gru_codigo_favorecido'),
                'gestao'              => $parameters->getValueByName('gru_gestao'),
                'codigo_correlacao'   => $parameters->getValueByName('gru_codigo_correlacao'),
                'nome_favorecido'     => $parameters->getValueByName('gru_nome_favorecido'),
                'codigo_recolhimento' => $parameters->getValueByName('gru_codigo_recolhimento'),
                'nome_recolhimento'   => $parameters->getValueByName('gru_nome_recolhimento'),
                'competencia'         => '09/2020',       
            ]
        ];
    }
}