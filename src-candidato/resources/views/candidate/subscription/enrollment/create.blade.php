<p>
    Durante o período de matrícula, você pode imprimir um Atestado de Vaga, com validação eletrônica, para utilização
    para diversos fins
    <a href="{{ route('candidate.subscription.enrollment-process.show',['subscription'=>$subscription,'enrollment_process'=>1]) }}"
       class="bg-blue-500 hover:bg-blue-700  text-white font-bold py-2 px-3 mt-10 rounded-md inline-flex items-center my-1">
        Atestado de Vaga
    </a>
</p>
<form action="{{ route('candidate.subscription.enrollment-process.destroy',[
'subscription'=>$subscription->id,
'enrollment_process'=>$hasPending->id ?? 0,
]) }}" method="POST" id="delete-form">
    @csrf
    @method('delete')
</form>
<form x-show="!hasPending" @if($hasPending) class="hidden" @endif
action="{{ route('candidate.subscription.enrollment-process.store',['subscription'=>$subscription]) }}"
      id="enrollment-form" method="POST">
    @csrf
    <br>
    @if(is_null($hasPending) && !$acceptedTerms /** matricula ainda não submetida */ )
    <span class="text-2xl font-open-sans uppercase font-bold">Aceite os termos do Edital {{ $subscription->notice->number}} para prosseguir:</span>
    <br>
    <br>
    @include('candidate.subscription.enrollment.terms_of_consent')
    @endif
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <button type="submit" x-on:click="submit" x-show="!hasPending" class="float-right bg-green-500 hover:bg-green-700 text-2xl text-white font-bold py-2 px-3 mt-10 rounded-md inline-flex items-center my-1
        w-full" id="send-form">
                <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                     fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd" />
                </svg>
                <span class="uppercase">Iniciar Solicitação Matrícula</span>
            </button>
        </div>
    </div>
</form>

<div x-show="hasPending && !waitingFeedback" id="documents-area" class="hidden">
    @php $requiredsOk = true @endphp
    <x-jet-validation-errors class="mb-4 bg-red-200 p-2 border-2 border-red-700" />
    <div class="grid grid-cols-12">
        <p class="text-red-700 col-span-12">Atente para os documentos que são obrigatórios de acordo com a descrição de
            cada um.
            Cada arquivo deve ter no máximo {{$uploadMaxSize}} MB de tamanho e deve estar no formato pdf.
        </p>
        @if($hasPending->feedback ?? false)
            <div class="border-red-700 border text-red-700 bg-red-200 p-2 w-full col-span-12 rounded mt-2">
                <h3 class="font-bold">Análise referente a sua matrícula</h3>
                <p>{{$hasPending->feedback}}</p>
                <p class="text-sm text-gray-600">Enquanto o período de matrícula permanecer você pode excluir ou enviar
                    novos documentos, fique atento às análises
                    de cada um dos documentos
                </p>
            </div>
        @endif
        @foreach ($documentTypes as $documentType)
            <div class="md:col-span-12 col-span-12 border-gray-300 border-2 rounded-t-lg mt-4 p-2 bg-gray-100">
                <h2 class="text-2xl font-bold">{{ $documentType->title }} </h2>
                @if ($documentType->required)
                    @php
                        if($sendedDocuments->where('enrollment_process_id','=',$hasPending->id ?? 0)->where('document_type_id','=',$documentType->id)->count() > 0 && $requiredsOk != false) {
                            $requiredsOk = true;
                        } else {
                        $requiredsOk = false;
                    }
                    @endphp
                    <p class="text-sm text-red-700">OBRIGATÓRIO para todos os candidatos</p>
                @else
                    <p class="text-sm text-blue-700">Obrigatório para candidatos descritos a seguir:</p>
                @endif
                <p>{{ $documentType->description }}</p>
            </div>
            <div class="md:col-span-4 col-span-12 border-gray-300 border md:rounded-bl-lg p-2">
                <h3>Enviar arquivo:</h3>
                @if($sendedDocuments->where('enrollment_process_id','=',$hasPending->id ?? 0)->where('document_type_id','=',$documentType->id)->count() < 3)
                    <form action="{{ route('candidate.subscription.enrollment-process.update',[
                    'subscription'=>$subscription,
                    'enrollment_process'=>$hasPending->id ?? 0
                ]) }}" method="POST" enctype="multipart/form-data" onsubmit="event.submitter.setAttribute('disabled','disabled')">
                        @method('put')
                        @csrf
                        <input type="hidden" name="document_type_id" value="{{$documentType->id}}" />
                        <input type="hidden" name="uuid" />
                        <x-jet-input class="h-full w-full bg-gray-100" type="file" accept="application/pdf"
                                     name="document" :required="$documentType->required" />
                        <button type="submit"  class="bg-blue-500 text-white p-2 mt-2 rounded  w-full">
                            Anexar
                        </button>
                    </form>
                @else
                    Não é possível enviar mais que 3 arquivos para este documento, você pode excluir um dos documentos
                    enviados e enviar outro no lugar.
                @endif
            </div>
            <div class="md:col-span-8  col-span-12 border-gray-300 border md:rounded-br-lg  p-2">
                <table>
                    <thead>
                    <th>Arquivo</th>
                    <th>Análise</th>
                    </thead>
                    <tbody>
                    @foreach ($sendedDocuments->where('enrollment_process_id','=',$hasPending->id ?? 0)->where('document_type_id','=',$documentType->id) as $doc)
                        <tr class="border-b @if($doc->feedback) bg-red-100  @endif">
                            <td class="p-2 border-r">
                                <a href="{{ $doc->url }}" target="_blank" data-link="{{ $doc->url }}"
                                   class="underline">Visualizar</a>
                                @if(($doc->feedback_user_id && $doc->feedback) || (!$doc->feedback_user_id && !$doc->feedback))
                                    <button type="submit" name="uuid" value="{{ $doc->uuid }}" form="delete-form"
                                            class="underline text-red-700">Excluir</button>
                                @endif
                            </td>
                            <td class="p-2">
                                @if($doc->feedback_user_id && !$doc->feedback)
                                    Documento conferido
                                @endif
                                {{ $doc->feedback }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
        <div class="col-span-12">
            @if(($hasPending->send_at ?? null) == null)
                @if(!$requiredsOk)
                    <div class="border-red-700 bg-red-300 text-red-700 p-2 mt-4 rounded">
                        <h3 class="text-xl">Ainda existem campos obrigatórios para envio</h3>
                        <p>Assim que enviar todos os documentos obrigatórios será possível enviar seu pedido de matrícula</p>
                    </div>
                @endif
                <button type="submit"
                        form="enrollment-form"
                        @if(!$requiredsOk) disabled="disabled" @endif
                        @if($requiredsOk)
                            name="set_send_at"
                        value="1"
                        @endif
                        class="w-full rounded bg-green-700 text-white p-5 text-2xl font-bold mt-5">
                    ENVIAR MINHA MATRÍCULA
                </button>
            @endif
        </div>
    </div>
</div>
