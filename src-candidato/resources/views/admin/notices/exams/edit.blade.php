<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Gerenciar gabarito" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens tip="Gabaritos" home="admin.notices.exams.index" :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="p-5">
            <x-jet-validation-errors class="mb-4" />
            <form action="{{ route('admin.notices.exams.store', ['notice'=>$notice]) }}" method="POST">
                @csrf                
                <h2 class="text-xl">Cadastro de Gabarito</h2>
                <hr>

                <div class="grid grid-cols-6">
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Título" />
                        <x-jet-input type="text" name="title" maxlenght="250" autofocus required :value="old('title') ?? $exam->title" />
                    </div>                 
                    <div class="col-span-2 p-5">
                        @if($exam->id)
                        @method('put')
                        <button type="submit" formaction="{{ route('admin.notices.exams.update',['notice'=>$notice, 'exam'=>$exam]) }}"
                            class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 m-4 border-b-4 border-orange-700 hover:border-orange-500 rounded">
                            Atualizar
                        </button>
                        @else                            
                            <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 m-4 border-b-4 border-green-700 hover:green-blue-500 rounded">
                            Cadastrar
                        </button>
                        @endif
                    </div>
                
            </form>
        </div>
    </div>

</x-manager.app-layout>
