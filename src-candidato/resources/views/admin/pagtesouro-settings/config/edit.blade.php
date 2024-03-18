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
        <x-manager.header tip="Pagtesouro > Ambiente">
            <x-manager.internal-navbar-itens />
        </x-manager.header>
    </x-slot>
      
    <div class="inline-block min-w-full px-10  overflow-hidden">
        <form id="payment-pagtesouro-form"
                action="{{ route('admin.pagtesouro-settings.config.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
            <div class="grid grid-cols-12 gap-4 py-5">
                <div class="md:col-span-12">
                    <div class="grid grid-cols-12 gap-4 py-5">
                        
            
                        <div class="col-span-12 md:col-span-12">
                            <label for="pagtesouro_token">Token</label>
                            <textarea id="pagtesouro_token" class="block mt-1 w-full form-input" name="pagtesouro_token"            
                        required>{{$pagTesouroParameters->pagtesouro_token}}</textarea>
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <x-jet-label value="Cod Serviço" />
                            <x-jet-input class="block mt-1 w-full" type="text" name="pagtesouro_cod_servico" value="{{$pagTesouroParameters->pagtesouro_cod_servico}}" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        
                        <div class="col-span-6 md:col-span-3">
                            <x-jet-label value="URL Solicitação Pagamento" />
                            <x-jet-input class="block mt-1 w-full" type="text" name="pagtesouro_url_solicitacao_pagamento"
                            value="{{$pagTesouroParameters->pagtesouro_url_solicitacao_pagamento}}" required  />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        
                        <div class="col-span-6 md:col-span-3">
                            <x-jet-label value="URL Consulta Pagamento" />
                            <x-jet-input class="block mt-1 w-full" type="text" name="pagtesouro_url_consulta_pagamento"
                            value="{{$pagTesouroParameters->pagtesouro_url_consulta_pagamento}}" required  />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-4">
                            @method('put')
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                                    Atualizar
                                </button>
                        </div>
                    </div>
                    
                </div><!--col 1-->
                
            </div>
            
            <div class="grid grid-cols-12 gap-4 py-5">
                <div class="md:col-span-8"></div>
                
            </div>
        </form>
    
    </div> 
    </div>
    
</x-manager.app-layout>
