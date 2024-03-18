<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process\AnswerCard;
use App\Models\Process\AnswerTemplate;
use App\Models\Process\Exam;
use App\Models\Process\Notice;
use App\Models\Process\Subscription;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\GraphViz\Exception;

class AnswerCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Notice $notice)
    {
        if(!$notice->hasProva())
            return back();

        $uploadMaxSize = (int) ini_get("upload_max_filesize") - 1;
        $exame = $notice->exam;
        if(empty($exame->id) or count($exame->answerTemplates) == 0){
            $message = "Para realizar a leitura dos cartões de respostas é necessário cadastrar o gabarito de respostas!";
            return redirect()->route('admin.notices.exams.index', ['notice' => $notice])->with('error', $message);
        }

        $total_subscriptions = Subscription::where('notice_id', $notice->id)->count();
        $total_answers_cards = AnswerCard::where('notice_id', $notice->id)->count();

        return view('admin.notices.readanswercard.index', compact(
            'notice',
            'uploadMaxSize',
            'total_answers_cards',
            'total_subscriptions'
        ));;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Notice $notice)
    {
        if(!$notice->hasProva())
            return back();

        try{
            DB::table($notice->getScoreTableNameForCriteriaId(2))->exists();
        }catch (QueryException $exception){
            return redirect()->back()->withErrors(["A tabela {$notice->getScoreTableNameForCriteriaId(2)} não foi criada, atualize o edital!","Operação cancelada!"]);
        }

        //Validação do arquivo
        $uploadMaxSize = (int) ini_get("upload_max_filesize");
        $this->validate($request, [
            'answer_cards' => ['required', 'file', "mimes:csv,txt","max:". ($uploadMaxSize * 1024)]
        ],['answer_cards.*' => "Somente arquivos do tipo csv ou txt e com no máximo $uploadMaxSize MB."]);

        //Validação/Inserção dos dados
        $separador = ';';
        $file = $request->file('answer_cards');
        $real_path = $file->getRealPath();
        $data = $this->readCSV($real_path, $separador);

        $exam = Exam::where('notice_id', $notice->id)->first();

        //é preciso garantir a ordem pelo número da questão (lembrando que ao anular questões ocorre um reordenamento dos índices no bd)
        $answer_templates = AnswerTemplate::where('exam_id',$exam->id)->orderBy('question_number')->get();

        $Subscriptions = $notice->subscriptions()->isHomologated()->select('subscriptions.id as i', 'subscriptions.subscription_number')->get();
        $array_inscricoes = array();
        foreach ($Subscriptions->toArray() as $item){
            $array_inscricoes[$item['subscription_number']] = $item['i'];
        }

        $array_answer_possibilities = array_map(function ($s){
            return strtoupper($s['right_answer']);
        }, ($exam->answerTemplates()->select('right_answer')->groupBy('right_answer')->get())->toArray());

        $qtd_questoes = count($exam->answerTemplates);

        $answer_cards_to_insert = array();

        $array_answer_cards_verification = array();

        $array_scores_to_insert = array();

        DB::beginTransaction();
        try {
            foreach ($data as $d){

                if(!key_exists($d[0], $array_inscricoes))
                    throw new \Exception("A inscrição $d[0] não pode ser processada pois não corresponde a nenhuma inscrição do edital selecionado");

                if(in_array($d[0], $array_answer_cards_verification))
                    throw new \Exception("A inscrição $d[0] aparece mais de uma vez no arquivo enviado.");

                if((count($d) - 2) <> $qtd_questoes)
                    throw new \Exception("Formatação inválida ou com quantidade de questões diferente do gabarito: " . implode($separador, $d));

                if($d[1] <> '_' && $d[1] <> 'F'){
                    throw new \Exception("Dado de presença inválido para a inscrição $d[0]: $d[1]");
                }

                for($i=2; $i<count($d); $i++){
                    if(!in_array($d[$i], $array_answer_possibilities) && $d[$i] <> '*' && $d[$i] <> '_'){
                        throw new \Exception("Dado de resposta inválido para a inscrição $d[0]: $d[$i]");
                    }
                }

                $ar = array(
                    'notice_id' => $notice->id,
                    'exam_id' => $exam->id,
                    'subscription_id' => $array_inscricoes[$d[0]],
                    'subscription_number' => $d[0],
                    'is_absent' => $d[1] === 'F',
                    'answers' => $d[1] === 'F' ? null : implode($separador, array_slice($d, 2)),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                    'created_at' => Carbon::now()->toDateTimeString()
                );
                array_push($answer_cards_to_insert, $ar);
                array_push($array_answer_cards_verification, $d[0]);

                array_push($array_scores_to_insert, $this->getScoreToInsert($answer_templates, $exam, $array_inscricoes[$d[0]], $d));
            }

            set_time_limit(0);
            ini_set('memory_limit', -1);

            AnswerCard::upsert(
                $answer_cards_to_insert,
                [
                    'subscription_id'
                ],
                [
                    'is_absent',
                    'answers',
                    'updated_at'
                ]
            );

            DB::table($notice->getScoreTableNameForCriteriaId(2))->upsert(
                $array_scores_to_insert,
                [
                    'subscription_id'
                ],
                [
                    'linguagens_codigos_e_tecnologias',
                    'matematica_e_suas_tecnologias',
                    'ciencias_humanas_e_suas_tecnologias',
                    'ciencias_da_natureza_e_suas_tecnologias',
                    'nota',
                    'updated_at',
                    'is_eliminated'
                ]
            );
        }
        catch (\PDOException $e){
            DB::rollBack();
            if(env('APP_DEBUG'))
                throw $e;
            else
                return redirect()->back()->withErrors(["Erro ao gravar os dados.","Operação cancelada!"]);
        }
        catch (\Exception $e){
            DB::rollBack();
            if(env('APP_DEBUG'))
                throw $e;
            return redirect()->back()->withErrors([$e->getMessage(),"Operação cancelada!"]);
        }
        DB::commit();
        return redirect()->route('admin.notice.readanswercard.index', ['notice' => $notice])
            ->with('success', count($answer_cards_to_insert) . " registros inseridos com sucesso!");
    }

    function readCSV($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = false;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                array_push($data, $row);
            }
            fclose($handle);
        }

        return $data;
    }


    private function getScoreToInsert($answer_templates, Exam $exam, $subscription_id, $cartao){
        //ini_set('max_execution_time', 300);

        $faltante = $cartao[1] === 'F' ? true : false;

        $answers = array_slice($cartao, 2);

        if(count($answer_templates) <> count($answers))
            throw new \Exception("Quantidade de questões não bate com a quantidade do template de respostas.");

        $linguagens = 0;
        $matematica = 0;
        $ciencias_natureza = 0;
        $ciencias_humanas = 0;

        for($i = 0; $i < count($answer_templates); $i++){
            //contador de acerto
            $ponto_acerto = 0;

            //gabarito da questão           
            //$answer_template = $answer_templates->firstWhere('question_number', $i+1); --> abordagem segura, mas lenta
            $answer_template = $answer_templates[$i];
            
            //se a questão for anulada ou se a resposta do gabarito (template) for igual à resposta do cartão, ocorre a pontuação.
            if(($answer_template->is_canceled) || (strtoupper($answer_template->right_answer) === strtoupper($answers[$i]))){
                $ponto_acerto = 1 * $answer_template->weight;
            }
            
            //contabiliza a pontuação por área do conhecimento
            if($ponto_acerto > 0){
                switch ($answer_template->area_id) {
                    case 1:
                        $linguagens += $ponto_acerto;
                        break;
                    case 2:
                        $matematica += $ponto_acerto;
                        break;
                    case 3:
                        $ciencias_natureza += $ponto_acerto;
                        break;
                    case 4:
                        $ciencias_humanas += $ponto_acerto;
                        break;
                    default:
                        break;
                }
            }
            
        }

        return array(
            'subscription_id'   =>  $subscription_id,
            'linguagens_codigos_e_tecnologias'   => $linguagens,
            'matematica_e_suas_tecnologias' => $matematica,
            'ciencias_humanas_e_suas_tecnologias' => $ciencias_humanas,
            'ciencias_da_natureza_e_suas_tecnologias' => $ciencias_natureza,
            'nota' => $linguagens + $matematica + $ciencias_natureza + $ciencias_humanas,
            'updated_at' => Carbon::now()->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString(),
            'is_eliminated' => $faltante
        );
    }

}
