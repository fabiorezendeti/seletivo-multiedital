<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Pagamentos realizados por GRU" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens home="admin.notices.read-gru-file.index" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por candidato" />
        </x-manager.header>
    </x-slot>

    <div class=" overflow-x-auto">
        <div class="grid grid-cols">
            <div class="px-5 py-5  inline-block min-w-full  overflow-hidden  md:col-span-3">
                <div class="col-span-6">
                    <h2 class="border-gray-700 border-b-2">Enviar o arquivo em formato XML do SISGRU</h2>
                </div>
                <x-jet-validation-errors class="mb-4" />
                <form action="{{ route('admin.notices.read-gru-file.store',['notice'=>$notice]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf                    
                    <x-jet-label value="Arquivo" />
                    <x-jet-input type="file" name="gru_document" accept=".xml" />
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit"  class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4
                        border-blue-700 hover:border-blue-500 rounded">
                            Enviar e Analisar
                        </button>                        
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-manager.app-layout>