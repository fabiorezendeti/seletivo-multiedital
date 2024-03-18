<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Usuários" > 
            <x-manager.internal-navbar-itens tip="Usuários" home="admin.users.index"  search-placeholder="Buscar por nome, cpf ou e-mail"/>
        </x-manager.header>        
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden"> 
                <table class="min-w-full leading-normal"> 
                    <thead> 
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nome
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                CPF
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                E-mail
                            </th>                            
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">                                
                                    {{$user->name}}                                
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$user->cpf}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$user->email}}
                                @if($user->email_verified_at)
                                <span class="bg-green-100 text-green-500 rounded-full p-2">confirmado</span>
                                @else
                                <span class="bg-red-100 text-red-500 rounded-full p-2">pendente</span>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="{{route('admin.users.edit',['user'=>$user])}}" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Editar
                                </a>
                            </td>
                        </tr>
                        @endforeach    
                    </tbody>
                </table>
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>