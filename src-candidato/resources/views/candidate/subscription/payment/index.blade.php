@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold">Pagamento</span>
<div>    
    <x-error-message /> 
    <form id="payment-pagtesouro-form"
            action="{{ route('candidate.subscription.payment.store',['subscription'=>$subscription]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
        <div class="grid grid-cols-6 gap-4 py-5">
            <div class="col-span-6">
            <h2 class="border-gray-300 border-b-2">Confirme os dados abaixo. Se houver algum dado errado faça contato pelo e-mail: {{ App\Repository\ParametersRepository::getValueByName('email_instituicao') }}</h2>
            </div>

            <div class="col-span-6 md:col-span-1">
                <x-jet-label for="payment_reference" value="Referência (nº de inscrição)" />
                <x-jet-input id="payment_reference" type="text" class="block mt-1 w-full" name="payment_reference"            
                    required value="{{$subscription->subscription_number}}" readonly="readonly" />
            </div>
        </div>
        <div class="grid grid-cols-6 gap-4 py-5">
            <div class="col-span-6 md:col-span-1">
                <x-jet-label value="CPF" />
                <x-jet-input class="mask-cpf block mt-1 w-full" type="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"
                    name="payment_cpf" value="{{$subscription->user->cpf}}" required readonly="readonly" />
            </div>
            <div class="col-span-6 md:col-span-2">
                <x-jet-label value="Nome Completo" />
                <x-jet-input class="block mt-1 w-full" type="text" name="payment_name"
                    value="{{$subscription->user->name}}" required readonly="readonly" />
            </div>
        </div>
        <div class="grid grid-cols-6 gap-4 py-5">
            <div class="col-span-6 md:col-span-1">
                <x-jet-label for="payment_price" value="Valor" />
                <x-jet-input id="payment_price" type="text" class="block mt-1 w-full" name="payment_price"            
                    required value="{{number_format($subscription->notice->registration_fee, 2, ',','.')}}" readonly="readonly" />
            </div>
            <div class="col-span-6 md:col-span-2">
                <x-jet-label value="Vencimento" />
                <x-jet-input class="block mt-1 w-full" type="text" name="payment_date"
                    value="{{$subscription->notice->payment_date->format('d/m/Y')}}" required readonly="readonly" />
            </div>
        </div>
        <div class="grid grid-cols-12 gap-4 py-5">
            <div class="col-span-2">
                <a href="{{route('candidate.subscription.show',['subscription'=>$subscription])}}"
                        class="bg-orange-500 hover:bg-orange-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="uppercase">Voltar</span>
                </a>
            </div>
            
            <div class="col-span-3">
                <button type="submit"
                        class="bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="uppercase">Prosseguir Pagamento</span>
                </button>
            </div>
        </div>
    </form>

  
</div>
@endsection

