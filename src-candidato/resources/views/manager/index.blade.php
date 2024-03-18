<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Gerência" >        
            <x-manager.internal-navbar-itens  />
        </x-manager.header>
    </x-slot>

    <div class="grid grid-cols-6 gap-4 py-5">
        @foreach ($notices as $notice)
        <!--card ini-->
        <div class="col-span-6 md:col-span-3 lg:col-span-2 max-w-sm ml-5">
            <div class="max-w-sm rounded overflow-hidden shadow-md border-b-4 border-blue-700 hover:border-blue-500 rounded">
                <div class="px-3 py-4">
                  <div class="font-bold text-xl mb-2">{{$notice->number}}</div>
                  <p class="text-gray-700 text-base">
                    {{$notice->description}}
                  </p>
                </div>
                <div class="px-6 pt-4 pb-2 mb-2">
                    @can('isAdmin',$notice)
                    <a href="{{ route('admin.notices.show', ['notice'=>$notice] )}}" class="bg-blue-500 hover:bg-blue-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                        Gerenciar
                    </a>
                    @endcan
                    @can('hasOfferInMyCampuses', $notice)
                    <a href="{{ route('cra.notices.show', ['notice'=>$notice] )}}" class="bg-blue-500 hover:bg-blue-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                        Gerenciar
                    </a>
                    @endcan
                    @cannot('hasOfferInMyCampuses', $notice)
                    <p class="text-gray-500 italic">Não existe oferta para seu campus</p>    
                    @endcannot
                </div>
              </div>
        </div>
        @endforeach
    </div>
</x-manager.app-layout>