@extends('layouts.candidate.app')
@section('content-app')
<x-success-message />
<x-error-message />
<span class="text-2xl font-open-sans uppercase font-bold">Matrículas no edital {{ $subscription->notice->number}}</span>

<div class="grid grid-cols-12 gap-4 md:py-5 text-gray-600" x-data="edit()"
  x-init="checkPending({{ ($hasPending) ? 1 : 0 }},{{ $waitingFeedback}})">
  @can('allowEnrollmentProcess',$subscription)
  <div class="col-span-12 text-green-700 md:col-span-12 border border-t-4 border-green-700 rounded-md px-5 py-2">
    <h2 id="enroll-title" class="text-lg font-bold uppercase"><span
        class="borde-0 border-l-2 border-green-700 pl-4"></span>
      Matricular
    </h2>
    <div class="p-5">
      @include('candidate.subscription.enrollment.create')
    </div>
  </div>
  @else
  <div class="col-span-12 text-red-700 md:col-span-12 border border-t-4 border-red-700 rounded-md px-5 py-2">
    <h2 id="enroll-title" class="text-lg font-bold uppercase"><span
        class="borde-0 border-l-2 border-red-700 pl-4"></span>
      Ops!
    </h2>
    <div class="p-5">
      <p>Não existem períodos disponíveis para matrícula</p>
    </div>
  </div>
  @endcan
  <div class="col-span-12 md:col-span-12 border border-t-4 border-gray-400 rounded-md px-5 py-2">
    <h2 class="text-lg font-bold uppercase"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
      Procedimentos de matrícula finalizados
    </h2>
    <p class="text-gray-700 text-base">
      Os documentos quando enviados são listados abaixo, você pode clicar em cada um
        deles para visualizar e conferir se está correto. Quando o status da matrícula estiver "pendente" você pode
        alterar os documentos, caso alguém já tenha
        revisado seu pedido de matrícula e algum problema
        for encontrado as observações ficarão disponíves no campo "Análise da sua solicitação".
        A análise da solicitação será emitida apenas para documentos com problemas ou se algo estiver faltando,
        caso contrário seu pedido de matrícula será
        aceito ou recusado.
    </p>
    <div class="col-span-12">
      @forelse($enrollmentProcess->sortBy('id') as $enroll)
      <div class="rounded overflow-hidden shadow-md border-b-4 rounded
      @if($enroll->status == 'matriculado') bg-green-200  @else bg-red-200 @endif">
        <div class="px-3 py-4">
          <div class="font-bold text-xl mb-2">
            <p>Chamada {{ $enroll->call_number }}
              <span class="text-sm">Status {{ $enroll->status }}</span>
            </p>
          </div>
          <div class="grid grid-cols-3 bg-white">
            <h3 class="font-bold col-span-3">Documentos enviados:</h3>
            @foreach ($sendedDocuments->where('enrollment_process_id',$enroll->id) as $docs)
            <div class="px-2 py-2 text-sm border border-gray-400 m-2 md:col-span-1 col-span-3">
              <h4 class="font-bold p-2">{{ $docs->document_title }}</h4>
              <a class="@if($docs->feedback) bg-red-500 @else bg-green-500 @endif bg-opacity-75 text-white p-2 m-2 rounded block"
                target="_blank" href="{{ $docs->url }}">
                Conferir Documento
              </a>
              <div class="p-2">
                @if($docs->feedback)
                <h4 class="font-bold">Análise</h4>
                <p>
                  {{ $docs->feedback }}.
                  @endif
                </p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        <div class="px-6 pt-4 pb-2 mb-2">
          Feedback geral: {{ $enroll->feedback ?? '--' }}
        </div>
      </div>
      @empty
      <p class="text-gray-700 text-base m-2 border border-gray-300 p-2 rounded">
        Nenhum procedimento de matrícula foi solicitado.
      </p>
      @endforelse
    </div>

  </div>
</div>

<a href="{{route('candidate.subscription.show',['subscription'=>$subscription])}}"
  class="bg-orange-500 hover:bg-orange-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
  <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
    <path fill-rule="evenodd"
      d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"
      clip-rule="evenodd" />
  </svg>
  <span class="uppercase">Voltar</span>
</a>

@endsection


@push('js')

<script>

  function edit() {
    return {
      hasPending: false,
      waitingFeedback: false,
      checkPending: function (pending, waitingFeedback) {
        if (pending) {
          let documentsArea = document.querySelector('#documents-area')
          documentsArea.removeAttribute('class')
          this.hasPending = pending
        }
        this.waitingFeedback = waitingFeedback
      },
      submit: function () {
        let form = document.querySelector('#enrollment-form')
        let span = document.querySelectorAll('.upload-max-limit')

        form.setAttribute('disabled', 'disabled')
        form.submit()
      }
    }
  }

</script>

@endpush
