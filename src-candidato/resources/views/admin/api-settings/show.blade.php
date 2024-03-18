<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Tokens">            
                <x-manager.internal-navbar-itens home="admin.api-settings.index" />            
        </x-manager.header>
    </x-slot>
    @if($msg)
    <div class="container bg-orange-100 border-0 border-b-4 border-gray-300 rounded-md mx-auto my-24 min-h-full">
    {{$msg}}     
    </div>
    @else
    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <form action="{{route('admin.api-settings.update')}}" method="POST" id="update-token">
                    @csrf
                    @method('put')
                    <input type="hidden" name="user_id" value="{{$user->id}}">        
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nome
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                E-mail
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                CPF
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->name }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->email }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->cpf }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <button type="submit"
                                    class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 m-4 border-b-4 border-orange-700 hover:border-orange-500 rounded">
                                    Trocar token
                                </button>
                            </td>
                        </tr> 
                    </tbody>
                </table>
                </form>
            </div>
        </div>
    </div>
    @endif

</x-manager.app-layout>