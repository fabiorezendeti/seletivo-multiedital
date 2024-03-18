<div class="col-span-3 md:col-span-2 ml-2 mt-1 mb-1 flex flex-row-reverse">
    @if(isset($home))
        <a href="{{ route($home,$routeVars) }}" id="bt_list"
        class="mx-3 inline bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
            Listar
        </a>
    @endif
    @if(isset($create))
        <a href="{{ route($create,$routeVars) }}" id="bt_create"
        class="mx-3 inline bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
        {{ $buttonName ?? 'Novo' }}
        </a>
    @endif
    @if(isset($backUrl))
        <a href="{{ route($backUrl,$routeVars) }}" id="bt_list"
           class="mx-3 inline bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
            Voltar
        </a>
    @endif
</div>
<div class="col-span-6 md:col-span-2 flex h-10 md:ml-20 mt-2 justify-items-auto">
    @if(isset($searchPlaceholder))
        <form class="w-full max-w-sm">
        <div class="flex items-center">
            <input
            class="appearance-none bg-transparent border-b w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
            type="text" placeholder="{{ $searchPlaceholder }}" name="search" aria-label="Full name"
            value="{{ request('search') }}">
            <button class="flex-shrink-0 bg-gray-400 hover:bg-gray-300 text-white font-bold rounded p-1" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
            </svg>
            </button>
        </div>
        </form>
    @endif
</div>
