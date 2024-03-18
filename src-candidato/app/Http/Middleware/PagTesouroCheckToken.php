<?php

namespace App\Http\Middleware;

use App\Repository\ParametersRepository;
use Closure;
use Illuminate\Http\Request;

class PagTesouroCheckToken
{

    private ParametersRepository $parametersRepository;

    public function __construct(ParametersRepository $parametersRepository)
    {
        $this->parametersRepository = $parametersRepository;
    }

    public function handle(Request $request, Closure $next)
    {
        $pagTesouroParameters = $this->parametersRepository->getPagTesouroParameters();        
        $token_request = $request->header('Authorization');        
        if ($token_request === $pagTesouroParameters->pagtesouro_token) {            
            return $next($request);
        }        
        return response(
            [
                'codigo'    => '406-Not-Acceptable',
                'descricao' => 'Token Authorization não é o mesmo da requisição de pagamento.'
            ],
            406
        )->header('Content-Type', 'application/json');
    }
}
