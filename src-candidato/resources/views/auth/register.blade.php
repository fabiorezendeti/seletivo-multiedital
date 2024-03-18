@extends('layouts.candidate.guest')

@section('content-app')
    <x-jet-validation-errors class="mb-4"/>
    <span class="text-2xl font-open-sans uppercase font-bold">Cadastro</span>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="grid grid-cols-6 gap-4 py-10" x-data="{open: false}">
            <div class="col-span-6 md:col-span-3">
                <x-jet-label for="name" value="Seu Nome Completo"/>
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                             maxlength="200" required
                             autofocus autocomplete="name"/>
            </div>
            <div class="col-span-6 md:col-span-3">
                <x-jet-label for="social_name" value="Nome Social (opcional)"/>
                <x-jet-input id="social_name" class="block mt-1 w-full" type="text" name="social_name" maxlength="100"
                             :value="old('social_name')"/>
            </div>


            <div class="col-span-6 md:col-span-3 xl:col-span-1">
                <x-jet-label for="birth_date" value="Data de nascimento"/>
                <x-jet-input id="birth_date" class="mask-date block mt-1 w-full" type="data" placeholder="dd/mm/aaaa"
                             name="birth_date"
                             :value="old('birth_date')" required/>
            </div>

            <div class="col-span-6 md:col-span-3 xl:col-span-1">
                <x-jet-label for="username" value="CPF"/>
                <x-jet-input class="mask-cpf block mt-1 w-full" type="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"
                             placeholder="000.000.000-45" name="cpf" :value="old('cpf')" required
                             id="username"/>
            </div>

            <div class="col-span-6 md:col-span-3 xl:col-span-1">
                <x-jet-label for="rg" value="{{ __('RG') }}"/>
                <x-jet-input id="rg" class="block mt-1 w-full" type="text" name="rg" minlength="3" maxlength="20"
                             :value="old('rg')"
                             required/>
            </div>

            <div class="col-span-6 md:col-span-3 xl:col-span-1">
                <x-jet-label for="rg_emmitter" value="{{ __('Emissor do RG') }}"/>
                <x-jet-input id="rg_emmitter" class="block mt-1 w-full" type="text" name="rg_emmitter" minlength="4"
                             maxlength="40"
                             :value="old('rg_emmitter')" required/>
            </div>

            <div class="col-span-6 md:col-span-3">
                <x-jet-label for="mother_name" value="Nome completo da sua Mãe"/>
                <x-jet-input id="mother_name" class="block mt-1 w-full" type="text" name="mother_name" maxlength="150"
                             :value="old('mother_name')" required/>
            </div>

            <div class="col-span-6 md:col-span-3 xl:col-span-1">
                <x-jet-label for="is_foreign" value="Estrangeiro?"/>
                <input name="is_foreign" type="radio" @if(!old('is_foreign')) checked="checked" @endif value="0"
                       x-on:click="open = 0"
                       class="mt-2 form-checkbox h-8 w-8 text-gray-500"/>
                Não
                <input name="is_foreign" type="radio" @if(old('is_foreign') == 1) checked="checked" @endif value="1"
                       x-on:click="open = 1 "
                       class="form-checkbox mt-2 h-8 w-8 text-gray-500"/>
                Sim
                <x-jet-input-error for="is_foreign" class="mt-2"/>
            </div>
            <div class="col-span-6 md:col-span-3 xl:col-span-1" x-bind:class="{'hidden': open !== 1}">
                <x-jet-label for="nationality" value="Nacionalidade"/>
                <x-jet-input id="nationality" name="nationality" x-bind:required="open == 1" type="text"
                             class="mt-1 block w-full" :value="old('nationality')" maxlength="255"/>
                <x-jet-input-error for="nationality" class="mt-2"/>
            </div>
        </div>
        <div x-data="{email: '{{ old('email') ?? '' }}',emailConfirmation: '{{ old('email_confirmation') ?? '' }}'}"
             class="box-email grid grid-cols-6 gap-4 pb-5 border rounded border-red-300 p-3">
            <div class="col-span-6 md:col-span-3 xl:col-span-2">
                <x-jet-label for="email" value="{{ __('Email') }}"/>
                <x-jet-input id="email" x-model="email" class="block mt-1 w-full" type="email" name="email"
                             :value="old('email')"
                             required/>
            </div>
            <div class="col-span-6 md:col-span-3 xl:col-span-2">
                <x-jet-label for="email_confirmation" value="Confirmação de E-mail"/>
                <x-jet-input id="email_confirmation" x-model="emailConfirmation" class="block mt-1 w-full" type="email"
                             id="email_confirmation"
                             name="email_confirmation" :value="old('email_confirmation')" required/>
            </div>
            <div x-show="email !== emailConfirmation" class="col-span-6 md:col-span-6 lg:col-span-6">
                <p class="text-xs text-red-500">Os e-mails devem ser iguais</p>
            </div>
            <div class="text-email text-xs text-red-700 col-span-6 md:col-span-3 xl:col-span-2">
                <h2 class="text-sm font-medium pb-2">Atenção</h2>
                Ao preencher seu e-mail, certifique-se de informá-lo de forma correta para prevenir problemas com o seu
                cadastro!
            </div>
        </div>
        <div class="box-password grid grid-cols-6 gap-4 mt-5 mb-4 border rounded border-red-300 p-3">
            <div class="col-span-6 md:col-span-3 lg:col-span-2">
                <x-jet-label for="password" value="{{ __('Password') }}"/>
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required
                             autocomplete="new-password"/>
            </div>

            <div class="col-span-6 md:col-span-3 lg:col-span-2">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}"/>
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password"
                             name="password_confirmation" required
                             autocomplete="new-password"/>
            </div>
            <div class="text-password text-xs text-red-700 col-span-6 md:col-span-3 xl:col-span-2">
                <h2 class="text-sm font-medium pb-2">Atenção</h2>
                A senha deve ter no mínimo 8 caracteres, uma letra maiúscula e um número
            </div>
        </div>
        <livewire:contact/>
        <div class="grid grid-cols-6 gap-4 mt-8">
            <div class="col-span-6">
                <h2 class="border-gray-300 border-b-2">Termo de Consentimento Livre e Esclarecido</h2>
            </div>
            <div class="col-span-6 md:col-span-3">
                <x-modal title="TERMO DE CONSENTIMENTO LIVRE E ESCLARECIDO – TCLE" height="h-64"
                         class="modal-open bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 mb-3 border-b-4 border-blue-700 hover:border-blue-500 rounded"
                         buttonText="Clique aqui para ler o Termo">

                    <p class="py-3 text-justify">Este termo visa registrar a manifestação livre, informada e inequívoca
                        pela
                        qual o candidato, ao se inscrever nos processos seletivos do {{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }} -
                        {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }},
                        concorda com o tratamento de seus dados pessoais para finalidade específica, em conformidade com
                        a
                        Lei nº 13.709 – Lei Geral de Proteção de Dados Pessoais (LGPD).
                    </p>
                    <p class="py-3 text-justify">
                        Ao manifestar sua aceitação para com o presente termo, o responsável legal pelo menor consente e
                        concorda que O Instituto Federal Catarinense, representado pela Coordenação Geral de Avaliação e
                        Ingresso, fique autorizado a coletar dados e a realizar o tratamento dos seguintes dados
                        pessoais:
                    </p>
                    <p class="font-bold">
                        - Informações de contato (endereço, e-mail, telefone);
                    </p>
                    <p class="font-bold">
                        - Informações pessoais (Nome, RG, CPF, data de nascimento, nome da mãe);
                    </p>
                    <p class="font-bold">
                        - Informações das Ações Afirmativas;
                    </p>
                    <p class="py-3 text-justify">
                        O tratamento dos dados pessoais listados neste termo tem as seguintes finalidades:
                    <ul>
                        <li>- Possibilitar que a Coordenação Geral de Avaliação e Ingresso identifique e entre em
                            contato com o candidato, ou seu responsável legal no caso de menores de idade, para fins
                            informativos;
                        </li>
                        <li>- Possibilitar que a Coordenação Geral de Avaliação e Ingresso utilize tais dados na
                            inscrição e divulgação das etapas do processo de ingresso para o curso técnico integrado ao
                            ensino médio;
                        </li>
                        <li>- Possibilitar que o candidato seja classificado dentro das ações afirmativas para qual se
                            inscreveu;
                        </li>
                        <li>- Possibilitar que a Coordenação Geral de Avaliação e Ingresso utilize tais dados na
                            elaboração de relatórios.
                        </li>
                    </ul>
                    </p>
                    <p class="py-3 text-justify">
                        A Coordenação Geral de Avaliação e Ingresso responsabiliza-se pela manutenção de medidas de
                        segurança, técnicas e administrativas aptas a proteger os dados pessoais de acessos não
                        autorizados.
                        Em conformidade ao art. 48 da Lei nº 13.709, o Controlador comunicará ao Titular e à Autoridade
                        Nacional de Proteção de Dados (ANPD) a ocorrência de incidente de segurança que possa acarretar
                        risco ou dano relevante ao Titular.
                    </p>
                    <p class="py-3 text-justify">
                        A Coordenação Geral de Avaliação e Ingresso manterá os dados pessoais do candidato mesmo após
                        findado o processo de ingresso, para fins de registro.
                    </p>
                    <p class="py-3 text-justify">
                        Este consentimento poderá ser revogado pelo responsável legal pelo menor , a qualquer momento,
                        mediante solicitação via e-mail ou correspondência ao Controlador.
                    </p>

                </x-modal>
                <label class="block items-center">
                    <input type="checkbox" class="form-checkbox h-8 w-8 text-gray-500" required name="check-lgpd"
                           value="0">
                    <span class="ml-2 text-gray-500">Concordo com o termo de consentimento</span>
                </label>
            </div>
        </div>
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <button id="register_button"
                    class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-green-700 hover:border-green-500 rounded">
                <span> {{ __('Register') }}</span>
            </button>
        </div>
    </form>
@endsection


@push('js')

    <script>
        window.onload = () => {
            const myInput = document.getElementById('email_confirmation');
            myInput.onpaste = e => e.preventDefault();
        }

        $(function () {

            function toGreen(classBox, classText) {
                $(classBox).removeClass("border-red-300").addClass("border-green-500");
                $(classText).removeClass("text-red-700").addClass("text-green-700");
            }

            function toRed(classBox, classText) {
                $(classBox).removeClass("border-green-500").addClass("border-red-300");
                $(classText).removeClass("text-green-700").addClass("text-red-700");
            }

            $("#email_confirmation").on("change paste keyup", function () {
                if ($(this).val() == $('#email').val()) {
                    toGreen('.box-email', '.text-email');
                } else
                    toRed('.box-email', '.text-email');
            });
            $("#email").on("change paste keyup", function () {
                if ($(this).val() == $('#email_confirmation').val()) {
                    toGreen('.box-email', '.text-email');
                } else {
                    toRed('.box-email', '.text-email');
                }
            });

            $("#password_confirmation").on("change paste keyup", function () {
                if (($(this).val() == $('#password').val()) && ($(this).val().length >= 8)) {
                    toGreen('.box-password', '.text-password');
                } else
                    toRed('.box-password', '.text-password');
            });
            $("#password").on("change paste keyup", function () {
                if (($(this).val() == $('#password_confirmation').val()) && ($(this).val().length >= 8)) {
                    toGreen('.box-password', '.text-password');
                } else {
                    toRed('.box-password', '.text-password');
                }
            });
        });
    </script>

@endpush
