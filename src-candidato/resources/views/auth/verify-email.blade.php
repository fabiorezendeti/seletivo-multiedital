@extends('layouts.candidate.guest')

@section('content-app')
<x-jet-authentication-card>
    <x-slot name="logo">
        <img src="/img/logo_ifc.png" class="w-60 bg-gray-600 p-5 rounded">
    </x-slot>

    <div class="mb-4 text-sm text-gray-600">
        <p>
            Obrigado por se cadastrar! Antes de começar, precisamos confirmar seu endereço de e-mail.
            Por isso, nós enviamos um LINK para o e-mail que você utilizou no cadastro
            ({{ auth()->user()->email}}), que será utilizado por você para confirmar sua conta.
            Se você não recebeu o e-mail com o link, tente aguardar alguns minutos, ou reenviar! </p>
    </div>

    @if (session('status') == 'verification-link-sent')
    <div class="mb-4 font-medium text-sm text-green-600">
        Um novo link foi enviado para o e-mail que você utilizou durante o cadastro.
    </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="/email/verification-notification">
            @csrf

            <div>
                <x-jet-button
                    class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 mb-3 border-b-4 border-blue-700 hover:border-blue-500 rounded"
                    type="submit">
                    Reenviar e-mail de verificação
                </x-jet-button>
            </div>
        </form>

        <form method="POST" action="/logout">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                Sair
            </button>
        </form>
    </div>
    <div class="mt-4 flex items-center justify-between">
        <x-modal title="Prezado candidato!"
            class="modal-open float-right bg-gray-400 hover:bg-gray-500 text-sm text-white font-bold px-2 mr-3 rounded-md inline-flex items-center my-1"
            buttonText="Estou com problemas">
            <x-slot name="buttonIcon">
                <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                        clip-rule="evenodd" />
                </svg>
            </x-slot>
            <p>
                Se você está enfrentando dificuldades para acessar o Portal do Candidato ou não recebeu o
                e-mail de confirmação de cadastro, siga as seguintes instruções para corrigir o problema:
                <ul class="list-disc p-5">
                    <li>Certifique-se de acessar a conta de e-mail informada durante o cadastro.</li>
                    <li>Caso não tenha recebido o e-mail de confirmação, clique em “Reenviar e-mail de
                        Confirmação”
                    <li>Verifique a caixa de Spam do seu e-mail.</li>
                    <li>Para logar no sistema após o e-mail ser validado, o candidato deve acessar a página
                        inicial, <a href={{URL::to('/')}} target="_blank"> {{URL::to('/')}} </a>, clicar em “Entrar” e informar o CPF e senha
                        cadastrados.</li>
                    <li>Se nenhuma das ações anteriores corrigirem o problema, o candidato deve enviar
                        seu nome, cpf e e-mail para a Coordenação Geral de Avaliação e Ingresso através
                        do e-mail {{ App\Repository\ParametersRepository::getValueByName('email_instituicao') }} para relatar o seu problema.
                    </li>
                </ul>
            </p>
        </x-modal>
    </div>
</x-jet-authentication-card>
@endsection