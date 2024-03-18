<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Gerência do Edital" :notice="$notice" :nomeEdital="$notice->number">
            @can('isAdmin')
            <x-manager.internal-navbar-itens home="admin.notices.show" buttonName="Adicionar Oferta" create="admin.notices.offers.create" :routeVars="['notice'=>$notice]" search-placeholder="Buscar por curso ou campus" />
            @else
            <x-manager.internal-navbar-itens home="cra.notices.show" :routeVars="['notice'=>$notice]" search-placeholder="Buscar por curso ou campus" />
            @endcan
        </x-manager.header>
    </x-slot>

    <div class="grid grid-cols-12 rounded shadow-lg bg-white  border-b-4 border-blue-700 hover:border-blue-500 text-gray-600">
        <div class="col-span-12 md:col-span-6">
            <div class="p-4 rounded">
                <h2 class="font-bold text-xl mb-2">Informações</h2>
                <p class="text-gray-700 text-base">
                <p class="text-justify">Descrição: {{$notice->description }}</p>
                <p>Link: <a href="{{ $notice->link}}" target="_blank"> {{$notice->link}}</a> </p>
                <p>Inscrições de {{$notice->subscription_initial_date->format('d/m/Y') }} até
                    {{$notice->subscription_final_date->format('d/m/Y') }}
                </p>
                <p>Período de recursos de {{$notice->classification_review_initial_date->format('d/m/Y') }} até
                    {{$notice->classification_review_final_date->format('d/m/Y') }}
                </p>
                <p>Pagamento: @if($notice->has_fee) {{ Str::of($notice->registration_fee)->replace('.',',') }} até
                    {{ $notice->payment_date->format('d/m/Y') ?? null }} @else É gratuito @endif
                </p>
                <p>Formas de ingresso: {{ $notice->selectionCriterias->implode('details', ', ') }}</p>
                </p>
            </div>
        </div>
        <div class="col-span-12 md:col-span-6">
            <div class="p-4">
                <h2 class="font-bold text-xl mb-2">Opções</h2>
                @can('distributeLotteryNumber',$notice)
                <x-modal title="Distribuir números para Sorteio" class="modal-open bg-red-500 hover:bg-red-700 text-sm text-white font-bold px-2 mr-3 rounded-md inline-flex items-center my-1" buttonText="Distribuir números para Sorteio">
                    <p>O procedimento criará estrutura de banco de dados para sorteio e classificação, bem como
                        realizará a distribuição
                        dos números por ordem alfabética para cada um dos usuários inscritos e homologados em uma
                        oferta.
                        Este procedimento é completamente irreversível e não é possível sua execução mais que uma vez.
                        Se você tem certeza que deseja continuar clique em PROSSEGUIR!

                    </p>
                    <p class="bg-red-300 border-red-700 p-5 rounded">
                        Lembre, o processo é irreversível
                    </p>
                    <form action="{{ route('admin.notices.distribute-lottery-number',[
                        'notice'=>$notice
                    ]) }}" method="POST">
                        @csrf
                        @method('put')
                        <x-jet-danger-button type="submit">PROSSEGUIR</x-jet-danger-button>
                    </form>
                </x-modal>
                @endcan
                @can('isAdmin')
                    @if(!$notice->allSubscriptionsHomologated())
                        <x-modal title="Homologação em lote" class="modal-open bg-red-500 hover:bg-red-700 text-sm text-white font-bold px-2 mr-3 rounded-md inline-flex items-center my-1" buttonText="Homologação em lote">
                            <p>O procedimento irá aplicar o status HOMOLOGADO para todas as inscrições deste edital.</p>
                            <form action="{{ route('admin.notices.subscriptions.homologate-in-batch',[
                                'notice'=>$notice
                            ]) }}" method="POST">
                                @csrf
                                @method('put')
                                <x-jet-danger-button type="submit">EXECUTAR</x-jet-danger-button>
                            </form>
                        </x-modal>
                    @else
                        <x-modal title="Desfazer homologação em lote" class="modal-open bg-red-500 hover:bg-red-700 text-sm text-white font-bold px-2 mr-3 rounded-md inline-flex items-center my-1" buttonText="Desfazer homologação em lote">
                            <p>O procedimento irá desfazer a homologação de todas as inscrições deste edital.</p>
                            <form action="{{ route('admin.notices.subscriptions.revoke-homologate-in-batch',[
                            'notice'=>$notice
                        ]) }}" method="POST">
                                @csrf
                                @method('put')
                                <x-jet-danger-button type="submit">EXECUTAR</x-jet-danger-button>
                            </form>
                        </x-modal>
                    @endif
                @can('lotteryDrawAvailable',$notice)
                <a href="{{ route('admin.notices.lottery-draw.index',['notice'=>$notice]) }}" class="bg-red-500 mt-3 hover:bg-red-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Sorteio
                </a>
                @endcan
                @can('hasSISU',$notice)
                <a href="{{ route('admin.notices.offers.import.index',['notice'=>$notice]) }}" class="bg-blue-500 mt-3 hover:bg-blue-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Importar Ofertas
                </a>
                @endcan
                @can('allowAllocateExamRoom',$notice)
                <a href="{{  route('admin.notices.allocation-of-exam-room.index',['notice'=>$notice]) }}" class="bg-blue-500 mt-3 hover:bg-blue-700 text-xs
                    text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Ensalamento
                </a>
                @endcan
                @can('classificationReportAvailables',$notice)
                <a href="{{ route('admin.notices.classifications.index',['notice'=>$notice]) }}" class="bg-blue-500 mt-3 hover:bg-blue-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Relatórios de Classificação
                </a>
                @endcan
                @can('classificationAvailable',$notice)
                <form action="{{ route('admin.notices.classifications.store',['notice'=>$notice]) }}" method="POST">
                    @csrf
                    <button class="bg-red-500 mt-3 hover:bg-red-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                        Classificar
                    </button>
                </form>
                @endcan
                @can('callAvailable',$notice)
                <a href="{{ route('admin.notices.calls.index',['notice'=>$notice]) }}" class="bg-green-500 mt-3 hover:bg-green-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Chamadas
                </a>
                @endcan
                @can('undoClassificationAvailable',$notice)
                <form action="{{ route('admin.notices.classifications.destroy',['notice'=>$notice]) }}" method="POST">
                    @csrf
                    @method('delete')
                    <button class="bg-red-500 mt-3 hover:bg-red-700 text-xs
                            text-white font-bold py-2 px-3 mr-3 rounded-full">
                        Desfazer Classificação
                    </button>
                </form>
                @endcan
                @if(Route::has('admin.notices.criterias.show'))
                @foreach($notice->needsCustomization() as $criteria)
                <a href="{{ route('admin.notices.criterias.show',['notice'=>$notice,'criteria'=>$criteria]) }}" class="bg-gray-500 mt-3 hover:bg-gray-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Customizar {{ $criteria->description }} </a>
                @endforeach
                @endif
                @endcan
                @can('isAcademicRegister')
                @can('callAvailable',$notice)
                <a href="{{ route('cra.notices.calls.index',['notice'=>$notice]) }}" class="bg-green-500 mt-3 hover:bg-green-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Chamadas
                </a>
                @endcan
                @endcan
            </div>
        </div>
    </div>

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
                            @can('isAdmin')
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offers as $offer)
                        <tr class="border-b-2 border-gray-200">
                            <td class="p-3">{{$offer->courseCampusOffer->campus->name }}</td>
                            <td class="p-3">{{$offer->courseCampusOffer->course->name }}</td>
                            <td class="p-3">{{$offer->courseCampusOffer->shift->description }}</td>
                            <td class="p-3">{{$offer->total_vacancies }}</td>
                            <td class="p-3">
                                @can('isAdmin')
                                <a href="{{route('admin.notices.offers.edit',['notice'=>$notice,'offer'=>$offer])}}" class="bg-orange-500 mt-3 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Editar
                                </a>
                                <button type="submit"
                                    formaction="{{route('admin.notices.offers.destroy',['notice'=>$notice,'offer'=>$offer])}}" class="bg-red-500 mt-3 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full"
                                    form="delete-notice">
                                    Excluir
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $offers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</x-manager.app-layout>
