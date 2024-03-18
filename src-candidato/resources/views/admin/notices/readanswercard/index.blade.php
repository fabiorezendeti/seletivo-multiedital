<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Carregar Cartões de Respostas" :notice="$notice" :nomeEdital="$notice->number">
        </x-manager.header>
    </x-slot>

    <div id="subscription-table" x-data="subscription()">
        <div class="overflow-x-auto">
            <div id="filters" class="grid grid-cols">
                <div class="px-5 py-5 overflow-hidden md:col-span-3">
                    <x-jet-validation-errors class="mb-4" />
                    <form action="{{ route('admin.notice.readanswercard.store',['notice'=>$notice]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($total_answers_cards > 0)
                            <p class="text-red-700">Já existem cartões de resposta para esse edital.</p>
                        <br>
                            <p class="text-red-700">Foram importados {{$total_answers_cards}} cartões de respostas de um total de {{$total_subscriptions}} inscrições neste edital.</p>
                        @endif
                        <br>
                        <label for="answer_cards">Selecione o arquivo csv com cartões de respostas:</label>
                        <div class="grid grid-cols-6 gap-4">
                            <div class="col-span-3 sm:col-span-3 md:col-span-3">
                                <x-jet-input
                                    class="block mt-1 w-full" type="file" accept=".csv"
                                    name="answer_cards" required />
                                <x-jet-input-error for="answer_cards" class="mt-2" />
                                <p class="text-xs">O arquivo deve ter no máximo {{$uploadMaxSize}} MB de tamanho e deve estar no formato csv</p>
                            </div>
                            <div class="col-span-1 sm:col-span-1 md:col-span-1n">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4
                                    border-blue-700 hover:border-blue-500 rounded" style="margin-top: 6px">
                                    Enviar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-manager.app-layout>
