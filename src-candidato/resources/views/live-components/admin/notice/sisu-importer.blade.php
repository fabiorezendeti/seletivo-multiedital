<div>
    <div class="bg-blue-500 p-2 rounded text-white">
        <h2 class="font-bold mb-2">Algumas informações importantes</h2>
        <p>Salvamos o arquivo com o seguinte nome {{ $fileName }} para fins de histórico</p>
    </div>
    <h2 class="text-lg font-bold">Revise o vínculo das ações afirmativas:</h2>
    <p class="bg-red-300 text-red-700 p-2 rounded mb-4">As ações afirmativas não vinculadas não serão importadas.

    </p>
    <table class="w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-300 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Ação afirmativa</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-300 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Selecionar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($affirmativeActions as $k=>$a)
            <tr>
                <td>{{$a['sisu_imported']}}</td>
                <td>
                    <x-select-box wire:model="affirmativeActions.{{$k}}.affirmative_action_id">
                        <option value="">-- Vincule uma ação --</option>
                        @foreach ($affirmativeActionsAvailable as $av)
                        <option value="{{ $av['id'] }}" title="">{{ $av['slug'] }}</option>
                        @endforeach
                    </x-select-box>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="text-lg font-bold mt-4 border-t-2">Cursos Importados</h2>
    <p class="bg-red-300 text-red-700 p-2 rounded mb-4">Os cursos importados serão verificados pelo código SISU cadastrado na oferta de curso para cada campus.
        Os cursos não encontrados serão sinalizados. Nesse caso, cadastre o código de sisu corretamente e refaça o procedimento.
    </p>
    @foreach ($offers->groupBy('sisu_course_code',$preserveKeys = true) as $offer)
    <table class="w-full leading-normal">
        <thead>
            <tr>
                <th colspan="3" class="px-5 py-3 border-b-2 border-gray-200 bg-gray-400 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                    Encontrei no arquivo:
                    {{ $offer->first()['sisu_course_code'] }} - {{ $offer->first()['campus'] }} - {{ $offer->first()['course'] }} - {{ $offer->first()['shift'] }}
                    @if(!$offer->first()['course_campus_offer'])
                    <br />
                    <span class="px-5 bg-red-300 text-red-700">Curso Não encontrado, certifique de cadastrar o código sisu na oferta de curso</span>
                    @else
                    <br>
                    Encontrei cadastrado:
                    {{ $offer->first()['course_campus_offer']['sisu_course_code'] }} - CAMPUS {{ $offer->first()['course_campus_offer']['campus']['name'] }} - CAMPUS {{ $offer->first()['course_campus_offer']['course']['name'] }}
                    @endif
                </th>
            </tr>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-300 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Ação afirmativa</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-300 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Vagas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($offer as $k=>$o)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    {{ $o['affirmative_action'] }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    {{ $o['total_vacancy'] }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
    @if($offers->where('course_campus_offer','!=',null)->count() == 0)
    <p class="bg-red-300 text-red-700 p-2">
        Os cursos que não foram encontrados
    </p>
    @endif
    <div wire:loading.block class="bg-red-300 text-red-700 p-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
        </svg>
        Estamos Carregando as Configurações, um café vai bem!
    </div>
    @if ($allowImport)
    <div wire:loading.remove>
        <button class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded" type="button" wire:click="import()">
            Importar
        </button>
    </div>
    @else
    <p class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded">
        Você não pode importar nada, tem cursos que não foram encontrados pelo código do SISU
    </p>
    @endif
</div>