<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Controllers\LoginUnicoController;
use App\Models\Address\City;
use App\Models\Address\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Fortify::$registersRoutes;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // VIEW A SER EXIBIDA AO ACESSAR A ROTA LOGIN
        Fortify::loginView(function () {
            if(env('LOGIN_UNICO_ENABLE')){
                return view('candidate.welcome');
            }else{
                return view('auth.login');
            }
        });


        Fortify::registerView(function (Request $request){
            if(env('LOGIN_UNICO_ENABLE')){
                $nome = $request->session()->get('nome');
                $email = $request->session()->get('email');
                $cpf = $request->session()->get('cpf');
                if(is_null($cpf) or is_null($email)){
                    return redirect()->route('login');
                }
                return view('auth.register-login-unico',compact(
                    'nome',
                    'email',
                    'cpf'
                ));
            }else{
                return view('auth.register');
            }
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
    }
}
