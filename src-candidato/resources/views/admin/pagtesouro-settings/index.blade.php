<x-manager.app-layout>
    <x-slot name="header">
        <div id="config-header" class="shadow-md bg-blue-800 w-full top-0 text-white py-1 ">
            <div class="container mx-auto flex flex-wrap items-center justify-start mt-0 px-2 py-2 ">
              <ul>
                <li
                class="block md:inline md:float-left px-4 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
                <a href="{{ route('admin.pagtesouro-settings.index') }}">
                  Checagem de Ambiente
                </a>
              </li>
              </ul>
            </div>
        </div>
        <x-manager.header tip="Pagtesouro > Checagem">
            <x-manager.internal-navbar-itens />
        </x-manager.header>
    </x-slot>
   
    
    <div class="inline-block min-w-full px-10  overflow-hidden">
        <form id="payment-pagtesouro-form"
                action="{{ route('admin.pagtesouro-settings.payment.noStore') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
            <div class="grid grid-cols-12 gap-4 py-5">
                <div class="md:col-span-8">
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-4">
                        <h2 class="border-red-300 border-b-2">Esta funcionalidade realiza o pagamento de 1 centavo para confirmar se o ambiente de pagamentos está funcionando.</h2>
                        </div>
                        <div class="col-span-2"></div>
            
                        <div class="col-span-6 md:col-span-2">
                            <x-jet-label for="payment_reference" value="Referência"/>
                            <x-jet-input id="payment_reference" type="text" class="block mt-1 w-full" name="payment_reference"            
                        required value="{{mt_rand(1000000000, 9999999999)}}" readonly="readonly" />
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <x-jet-label value="CPF Válido" />
                            <x-jet-input class="mask-cpf block mt-1 w-full" type="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"
                        name="payment_cpf" value="{{$user->cpf}}" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        
                        <div class="col-span-6 md:col-span-3">
                            <x-jet-label value="Nome Completo" />
                            <x-jet-input class="block mt-1 w-full" type="text" name="payment_name"
                            value="{{$user->name}}" required  />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-6 md:col-span-1">
                            <x-jet-label for="payment_price" value="Valor" />
                            <x-jet-input id="payment_price" type="text" class="block mt-1 w-full" name="payment_price"            
                                required value="{{number_format('0.01', 2, '.','')}}" readonly="readonly" />
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <x-jet-label value="Vencimento" />
                            <x-jet-input class="block mt-1 w-full" type="text" name="payment_date"
                        value="{{date('d/m/Y')}}" required readonly="readonly" />
                        </div>
                    </div>
                </div><!--col 1-->
                <div class="col-span-4">
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-4">
                        <h2 class="border-green-300 border-b-2">Ambiente ativo:</h2>
                        </div>
                                        
                        <div class="col-span-3 md:col-span-3">
                            @if($envPagtesouro=='produção')          
                                <span class="text-sm px-2 font-medium bg-red-500 text-white rounded py-0.5">
                            @elseif($envPagtesouro=='homologação')
                                <span class="text-sm px-2 font-medium bg-green-500 text-white rounded py-0.5">
                            @else
                                <span class="text-sm px-2 font-medium bg-gray-400 text-white rounded py-0.5">
                            @endif
                                {{$envPagtesouro}} 
                                </span>
                        </div>
                        <div class="col-span-2">
                            <a href="{{ route('admin.pagtesouro-settings.config.edit') }}" class="bg-blue-700 hover:bg-blue-800 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                Alterar
                            </a>
                        </div>
                        
                    </div>
                </div><!--col 2-->
            </div>
            
            <div class="grid grid-cols-12 gap-4 py-5">
                <div class="md:col-span-8"></div>
                <div class="col-span-4">
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
    </div>
    
</x-manager.app-layout>
