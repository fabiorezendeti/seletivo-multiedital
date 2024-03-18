@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold">Pagamento - PagTesouro</span>
<div>
    <a href="{{route('candidate.subscription.show',['subscription'=>$subscription])}}"
        class="bg-orange-500 hover:bg-orange-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"
                clip-rule="evenodd" />
        </svg>
        <span class="uppercase">Voltar para Comprovante de Inscrição</span>
</a>
    @if($erro != null)
        <div class="grid grid-cols-6 gap-4 py-5">
            <div class="col-span-6">
            <h2 class="border-red-300 border-b-2">Ops! Tivemos um problema :(</h2>
            <p class="red-300">{{$erro}} </p>
            </div>
            <div class="col-span-6">
                <a href="{{route('candidate.subscription.show',['subscription'=>$subscription])}}"
                        class="bg-orange-500 hover:bg-orange-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="uppercase">Voltar</span>
                </a>
            </div>
        </div>
    @else
    <iframe class="iframe-epag" style="margin: 0;padding: 0; border: 0; width: 1px; min-width: 100%;"
    src="{{$paymentRequest->proxima_url}}"
        scrolling="no">
    </iframe>
    @endif
  
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.6/iframeResizer.min.js"></script>

    <script>
        iFrameResize({ heightCalculationMethod: "documentElementOffset" }, ".iframe-epag");
    </script>
@endsection


