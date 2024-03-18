<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Gabarito" :notice="$notice" :nomeEdital="$notice->number" >   
            @can('isAdmin')
            <x-manager.internal-navbar-itens tip="Gabarito"  create="admin.notices.exams.create"  home="admin.notices.exams.index"
            :routeVars="['notice'=>$notice ?? '']" search-placeholder="Buscar por nome"/>
            @else
            <x-manager.internal-navbar-itens tip="Gabarito"  home="admin.notices.exams.index" search-placeholder="Buscar por título"/>
            @endcan        
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Título
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="mx-5">Opções</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exams as $exam)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$exam->title}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex justify-start">
                                    <div class="mx-5">
                                        <a href="{{route('admin.notices.exams.answers.index',['notice'=>$notice,'exam'=>$exam])}}" id="bt_list"
                                            class="float-right bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-1 rounded"
                                            title="Cadastrar questões">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                        </a>    
                                    </div>
                                    <div class="mx-5">
                                        <a href="{{route('admin.notices.exams.edit',['notice'=>$notice,'exam'=>$exam])}}" id="bt_list"
                                            class="float-right bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-1 rounded"
                                            title="Alterar Título">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>    
                                    </div>
                                    <div class="mx-5">
                                        <form action="{{ route('admin.notices.exams.destroy', ['notice'=>$notice,'exam'=>$exam])}}" method="POST"
                                        style="display: inline;">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"
                                            class="float-right bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded"
                                            title="Apagar Gabarito">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            </button>
                                        </form> 
                                    </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $exams->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>
