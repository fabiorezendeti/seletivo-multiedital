<x-manager.app-layout>
<x-slot name="header">
        <x-manager.header tip="Tokens">
            <x-manager.internal-navbar-itens tip="Usuários" home="admin.api-settings.index"
                create="admin.api-settings.create"  />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="p-5">
            <x-jet-validation-errors class="mb-4" />
            <form action="{{route('admin.api-settings.store') }}" method="POST">
                @csrf                
                <h2 class="text-xl">Cadastro de Tokens da API</h2>
                <hr>
                <div class="grid grid-cols-6">
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Nome" />
                        <x-jet-input type="text" name="name" maxlenght="150" autofocus required  />
                    </div> 
                    <div class="col-span-2 p-5">
                        <x-jet-label value="E-mail" />
                        <x-jet-input type="email" name="email" maxlenght="50"  />
                    </div>      
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Password" />
                        <x-jet-input type="password" id="password" name="password"/>
                    </div>    
                    <div class="col-span-2 p-5">
                        <x-jet-label value="CPF" />
                        <x-jet-input class="block mt-1 w-full" type="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="000.000.000-45" name="cpf"  required autofocus />
                    </div>                    
                    
                    <div class="col-span-3">
                                            
                            <button type="submit"
                            class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                            Cadastrar
                        </button>
                   
                    </div>
            </form>
        </div>
    </div>
    </div>
</x-manager.app-layout>