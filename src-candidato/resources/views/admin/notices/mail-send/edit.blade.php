<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Notificar por e-mail" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens tip="Editais" :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">
        <div class="grid grid-cols">
            <div class="px-5 py-5 overflow-hidden md:col-span-3">
                <form action="{{ route('admin.notices.mail-send.sender',['notice'=>$notice]) }}" method="POST">
                    @csrf
                    <x-jet-label value="Título da mensagem" />
                    <x-jet-input type="text" name="subject" class="w-full" />
                    <x-jet-label value="Mensagem" />
                    <x-textarea name="message" class="w-full right-0" rows="20" />
                    <div class="flex mt-4">
                        <div class="flex-1">
                            <label class="block items-center my-2">
                                <input type="checkbox" class="form-checkbox h-8 w-8" name="is_ppi" value="1">
                                <span class="ml-2 ">Somente para candidatos do processo de aferição PPI</span>
                            </label>
                        </div>                        
                        <div class="flex-1">
                            <label class="block items-center my-2">
                                <input type="checkbox" class="form-checkbox h-8 w-8" name="status" value="pendente">
                                <span class="ml-2 ">Somente para pendentes na chamada</span>
                            </label>
                        </div>
                        <div class="flex-1">
                            <label class="block items-center my-2">
                                <input type="checkbox" class="form-checkbox h-8 w-8" name="only_subscribers" value="1">
                                <span class="ml-2 ">Somente para INSCRITOS ainda não homologados</span>
                            </label>
                        </div>
                        <div class="flex-1">
                            <label class="block items-center my-2">
                                <input type="checkbox" class="form-checkbox h-8 w-8" name="only_homologated" value="1">
                                <span class="ml-2 ">Somente para HOMOLOGADOS</span>
                            </label>
                        </div>                        
                        <div class="flex-1">
                            <x-jet-label value="Chamada" />
                            <x-select-box name="call_number" >
                                <option value="">-- Não selecionado --</option>
                                @foreach($calls as $call)
                                <option value="{{$call}}">{{$call}}</option>
                                @endforeach
                            </x-select-box>
                        </div>
                        <div class="flex-1">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                                ENVIAR
                            </button>
                        </div>
                    </div>                                        
                </form>
            </div>
        </div>
    </div>

</x-manager.app-layout>