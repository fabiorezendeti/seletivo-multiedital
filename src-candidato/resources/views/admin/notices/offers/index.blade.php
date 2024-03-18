<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Editais" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens tip="Editais"
                 home="admin.notices.offers.index" 
                 create="admin.notices.offers.create" 
                 :routeVars="['notice'=>$notice]" 
                 search-placeholder="Buscar por curso ou campus"/>
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <form action="#" method="POST" id="delete-notice">
                    @csrf
                    @method('delete')
                </form>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Campus
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Curso
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Turno
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Vagas
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offers as $offer)
                        <tr class="border-b-2 border-gray-200">
                            <td class="p-3">{{$offer->courseCampusOffer->campus->name }}</td>
                            <td class="p-3">{{$offer->courseCampusOffer->course->name }}</td>  
                            <td class="p-3">{{$offer->courseCampusOffer->shift->description }}</td>  
                            <td class="p-3">{{$offer->total_vacancies }}</td>
                            <td class="p-3"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $offers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>
