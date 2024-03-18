<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Candidate\PagTesouroController;
use App\Http\Controllers\API\UserToSigController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('pagtesouro/subscription/{subscription}/payment/', [PagTesouroController::class, 'update'])
    ->middleware('pagtesouro.checktoken')
    ->name('subscription.payment.update');



Route::get('/test', function(Request $request){
    return 'Authenticated';
});

/*
cadastro de usuário de API - rota desabilitada, pois há funcionalidade no painel da gerência para cadastrar tokens
caso deseja realizar o cadastro via postman deve habilitar
//Route::post('/create-account', [AuthController::class, 'createAccount'])->name('auth.create-account');
*/

//login usuário de API
Route::post('/signin', [AuthController::class, 'signin']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });
    Route::post('/sign-out', [AuthController::class, 'logout']);

    /* api consultada pelo SIGAA para pegar dados de candidatos no cadastro de aluno do SIG*/
    Route::get('/usertosig/{cpf}', [UserToSigController::class, 'show']);
});