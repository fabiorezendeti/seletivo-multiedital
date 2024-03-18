<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Cursos">
            <x-manager.internal-navbar-itens tip="Usuários" home="admin.courses.index" create="admin.courses.create"
                search-placeholder="Buscar por curso ou modalidade" />
        </x-manager.header>
    </x-slot>

           <form id="delete-action" method="POST">
               @csrf
               @method('delete')
           </form>
            <x-manager.vertical-align-cards>
                @foreach ($courses as $course)                
                <x-manager.vertical-align-card-item :text="$course->modality->description" :title="$course->name">                                         
                    <a href="{{route('admin.courses.edit',['course'=>$course])}}"
                        class="bg-orange-500 mt-3 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                        Editar
                    </a>
                    @can('updateOrDelete',$course)
                    <button type="submit" formaction="{{route('admin.courses.destroy',['course'=>$course])}}"
                        form="delete-action"
                        class="bg-red-500 mt-3 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                        Excluir
                    </button>
                    @endcan
                    </x-manager.vertical-align-card-item>
                @endforeach
            </x-manager.vertical-align-cards>
            
            <div class="inline-block min-w-full  overflow-hidden">
                {{ $courses->appends(request()->query())->links() }}
            </div>
        
</x-manager.app-layout>