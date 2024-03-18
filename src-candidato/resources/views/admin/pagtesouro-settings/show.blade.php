<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Pagtesouro > Checagem">
            <x-manager.internal-navbar-itens />
        </x-manager.header>
    </x-slot>
<div>
    <iframe class="iframe-epag" style="margin: 0;padding: 0; border: 0; width: 1px; min-width: 100%;"
    src="{{$paymentRequest->proxima_url}}"
        scrolling="no">
    </iframe>
</div>
<div class="inline-block min-w-full px-10  overflow-hidden">
    <form id="status-pagtesouro-form"
            action="{{ route('admin.pagtesouro-settings.payment.payment-status') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
        <div class="grid grid-cols-12 gap-4 py-5">
            
            <div class="col-span-4">
                <div class="grid grid-cols-6 gap-4 py-5">
                    <div class="col-span-4">
                    <h2 class="border-green-300 border-b-2">Ambiente de checagem:</h2>
                    </div>
                    <div class="col-span-2"></div>
            
                    <div class="col-span-3 md:col-span-2">            
                        @if($envPagtesouro=='produção')          
                                <span class="text-sm px-2 font-medium bg-red-500 text-white rounded py-0.5">
                        @elseif($envPagtesouro=='homologação')
                            <span class="text-sm px-2 font-medium bg-green-500 text-white rounded py-0.5">
                        @else
                            <span class="text-sm px-2 font-medium bg-gray-400 text-white rounded py-0.5">
                        @endif
                            {{$envPagtesouro}} 
                        </span>
                    </div>
                    
                    <input type="hidden" name="idPagamento" value="{{$paymentRequest->idPagamento}}">
                    
                </div>
            </div><!--col 2-->
            <div class="col-span-4">
                <button type="submit" formtarget="_blank"
                        class="bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="uppercase">Consultar Status</span>
                </button>
            </div>
        </div>
        
    </form>

</div> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.6/iframeResizer.min.js"></script>

    <script>
        iFrameResize({ heightCalculationMethod: "documentElementOffset" }, ".iframe-epag");
    </script>
</x-manager.app-layout>



