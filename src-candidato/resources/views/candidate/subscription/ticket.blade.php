@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold no-print">Sua inscrição</span>
<x-success-message />
<x-error-message />
<div class="grid grid-cols-12 gap-4 md:py-5 text-gray-600">
    @can('viewEnrollmentProcess',$subscription)
    <div class="col-span-12  no-print border border-gray-400 px-5 py-2 mb-5 bg-green-500">
        <h2 class="text-md font-bold uppercase mb-3 text-white"><span
                class="borde-0 border-l-2 border-gray-400 pl-4"></span>
            Acompanhamento de Matrículas
        </h2>
        <p class="text-white">Você foi aprovado em uma de nossas chamadas, clique em MATRÍCULA para:
            <ul class="text-white p-5">
                <li class="list-disc">Acompanhar uma matrícula já realizada</li>
                <li class="list-disc">Realizar ou atualizar uma matrícula, caso seja possível</li>
            </ul>
        </p>
        <a href="{{ route('candidate.subscription.enrollment-process.index',['subscription'=>$subscription]) }}"
            class="bg-green-700 hover:bg-green-400 hover:text-green-700 text-lg  text-white no-print font-bold py-2 px-3 mt-5  rounded-md inline-flex  my-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <span class="ml-2">MATRÍCULA</span>
        </a>
    </div>
    @endcan
    <div class="col-span-12 md:col-span-8 border border-t-4 border-gray-400 rounded-md px-5 py-2">
        @include('candidate.subscription.ticket-partial')

        <div class="col-span-12 px-5 py-5 text-center no-print">
            <a href="#" onClick="window.print()"
                class="bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                        clip-rule="evenodd" />
                </svg> <span>Salvar</span>
            </a>
        </div>
    </div>
    <div class="col-span-12 md:col-span-4 border border-t-4 border-gray-400 rounded-md px-5 py-2 no-print">

        @can('printCallStatus',$subscription)
        <div class="col-span-12  no-print border border-gray-400 px-5 py-2 mb-5">
            <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
                Acompanhamento de chamadas
            </h2>
            <table class="table">
                <thead>
                    <tr>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Chamada</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Posição na Chamada</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Ação Afirmativa</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tableItens as $item=>$result)
                    <tr>
                        @if($result)
                        <td class="text-center text-xs tracking-wider">{{ $item }}</td>
                        <td class="text-center text-xs tracking-wider">{{ $result->call_position }}</td>
                        <td class="text-center text-xs tracking-wider">{{ $result->affirmative_action_slug }} </td>
                        <td class="text-center text-xs tracking-wider">Aprovado</td>
                        @else
                        <td class="text-center text-xs tracking-wider">{{ $item }}</td>
                        <td class="text-center text-xs tracking-wider" colspan="3">Lista de Espera</td>

                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endcan
        @can('allowRequestPreliminaryClassificationRecourse', $subscription)
        <div class="col-span-12 px-5 py-5 no-print border border-gray-400 px-5 py-2 mb-5">
            <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
                Solicitação de Recurso
            </h2>
            <form action="{{ route('candidate.subscription.request-recourse',['subscription'=>$subscription]) }}"
                method="POST">
                @csrf
                <x-jet-label for="position" value="Colocação na Classificação Preliminar" />
                <x-jet-input id="position" type="number" name="position" class="w-full" min="0" step=1
                    required="required" />
                <x-jet-input-error for="position" />

                <x-jet-label for="justify" value="Justificativa" />
                <x-textarea id="justify" name="justify" class="w-full" rows="7" required="required" />
                <x-jet-input-error for="justify" />

                <button type="submit"
                    class="bg-red-500 hover:bg-red-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                    Enviar
                </button>
            </form>
        </div>
        @endcan
        @if($subscription->preliminary_classification_recourse)
        <div class="col-span-12  no-print border border-gray-400 px-5 py-2 mb-5">
            <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
                Recurso Solicitado
            </h2>
            <p>
                <b>Colocação na classificação preliminar:</b> {{
                $subscription->preliminary_classification_recourse['position'] }}
            </p>
            <p>
                <b>Justificativa:</b> {!! nl2br(e($subscription->preliminary_classification_recourse['justify'])) !!}
            </p>
            <p class="text-xs">Solicitação feita em {{ $subscription->preliminary_classification_recourse['date_ptBR']
                }}</p>
        </div>
        @endif
        <div class="col-span-12  no-print border border-gray-400 px-5 py-2">
            <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
                Dados de contato
            </h2>
            @if($subscription->user->contact)
            <p><b>Telefone (principal):</b>{{ $subscription->user->contact->phone_number }}</p>
            <p><b>Telefone (alternativo):</b>{{ $subscription->user->contact->alternative_phone_number }}</p>
            <p><b>CEP:</b>{{ $subscription->user->contact->zip_code }}</p>
            <p><b>Rua:</b>{{ $subscription->user->contact->street }}, {{ $subscription->user->contact->number }}</p>
            <p><b>Bairro:</b>{{ $subscription->user->contact->district }}</p>
            <p><b>Cidade:</b>{{ $subscription->user->contact->city->name }} -
                {{ $subscription->user->contact->city->state->name }}</p>
            @else
            <p>Você ainda não informou dados de contato</p>
            @endif
            <a href="{{ route('user.contact.edit') }}"
                class="bg-blue-500 hover:bg-blue-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                        clip-rule="evenodd" />
                </svg> <span>Atualizar</span>
            </a>
            <h2 class="text-2xl text-red-600 text-center">Importante!</h2>
            <p>Mantenha seus dados de contato atualizados para que se necessário o {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }} entre em contato mais fácil com
                você
            </p>
        </div>
    </div>
</div>

@endsection
