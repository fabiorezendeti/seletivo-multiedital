<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Cursos">
            <x-manager.internal-navbar-itens tip="Cursos" home="admin.courses.index" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="p-5">
            <x-jet-validation-errors class="mb-4" />
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf
                <h2 class="text-xl">Cadastro de Curso</h2>
                <hr>
                <div class="grid grid-cols-3">
                    <div class="col-span-3 p-5">
                        <x-jet-label value="Nome" />
                        <x-jet-input type="text" class="w-full" name="name" maxlenght="200" autofocus required id="name"
                            :value="old('name') ?? $course->name" />
                    </div>
                    <div class="p-5">
                        <x-jet-label value="Modalidade" />
                        <x-select-box name="modality_id" id="modality-id">
                            @foreach($modalities as $modality)
                            <option value="{{$modality->id}}" @if($modality->id == $course->modality_id)
                                selected="selected" @endif >{{$modality->description}}</option>
                            @endforeach
                        </x-select-box>
                    </div>
                    <div class="col-span-3">
                        @can('updateOrDelete',$course)
                        @if($course->id)                 
                        @method('put')
                        <button type="submit" formaction="{{ route('admin.courses.update',['course'=>$course]) }}"
                            class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                            Atualizar
                        </button>
                        @else
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                            Cadastrar
                        </button>                        
                        @endif
                        @endcan
                    </div>
            </form>



        </div>
        @if($course->id)
        <div class="p-5 border-t-2 border-gray-600">            
            <livewire:course.campus-offer :course-id="$course->id" />
        </div>
        @endif
    </div>



</x-manager.app-layout>