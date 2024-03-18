@if ($nomeEdital)
<div id="edital-header" class="shadow-md bg-blue-800 w-full top-0 text-white py-1 ">
  <div class="container mx-auto flex flex-wrap items-center justify-start mt-0 px-2 py-2 ">
    <a href="{{ Gate::allows('isAdmin') ? route('admin.notices.index') : route('cra.notices.index') }}" id="bt_list"
      class="mx-3 h-5 w-5 float-right bg-blue-500 hover:bg-blue-400 text-white font-bold py-1 px-1 rounded"
      title="Alterar Edital">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
      </svg>
    </a>
    @can('isAdmin')
    <a href="{{ ($notice->id) ?  route('admin.notices.show',['notice'=>$notice]) : '#' }}">
      <span class="text-white font-bold text-2xl mr-10">Edital {{$nomeEdital}}</span>
    </a>
    @else
    <a href="{{ ($notice->id) ?  route('cra.notices.show',['notice'=>$notice]) : '#' }}">
      <span class="text-white font-bold text-2xl mr-10">Edital {{$nomeEdital}}</span>
    </a>
    @endcan
    @if($notice->id)
    @can('isAdmin')
    <a href="{{ route('admin.notices.edit', ['notice'=>$notice] )}}"
      class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
      Editar
    </a>
    @endcan
    @can('isAdmin')
    @if($notice->hasProva())
    <ul class="">
      <li
      class="block md:inline md:float-left sm:z-50 px-4 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
        <x-jet-dropdown align="left" width="48">
          <x-slot name="trigger">
            <a href="#" class="inline text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
              Gabaritos
            </a>
            <svg class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </x-slot>

          <x-slot name="content">
            <x-jet-dropdown-link href="{{ route('admin.notices.exams.index',['notice'=>$notice]) }}">
                Cadastro de gabarito
              </x-jet-dropdown-link>
              <x-jet-dropdown-link href="{{ route('admin.notice.readanswercard.index',['notice'=>$notice]) }}">
                Leitura de cartões resposta
              </x-jet-dropdown-link>
              <x-jet-dropdown-link href="{{ route('admin.notices.readanswercard.fakegenerate',['notice'=>$notice]) }}">
                Gerar cartões resposta para testes
              </x-jet-dropdown-link>
          </x-slot>
        </x-jet-dropdown>
      </li>
    </ul>
    @endif
    @endcan
    <ul class="">
      <li
        class="block md:inline md:float-left sm:z-50 px-4 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
        <x-jet-dropdown align="left" width="48">
          <x-slot name="trigger">
            <a href="#" class="inline text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
              Inscrições
            </a>
            <svg class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </x-slot>

          <x-slot name="content">
            @can('isAdmin')
            <x-jet-dropdown-link href="{{ route('admin.notices.subscriptions.index',['notice'=>$notice]) }}">
              Inscritos
            </x-jet-dropdown-link>
            <x-jet-dropdown-link href="{{ route('admin.notices.subscriptions.ppi',['notice'=>$notice]) }}">
              Aferição PPI
            </x-jet-dropdown-link>
            @if($notice->modality_id === 1)
            <x-jet-dropdown-link href="{{ route('admin.notices.candidates-aged-18.report',['notice'=>$notice]) }}">
              Maiores de 18
            </x-jet-dropdown-link>
            @endif
            <x-jet-dropdown-link href="{{ route('admin.notices.recourses.index',['notice'=>$notice]) }}">
              Recursos
            </x-jet-dropdown-link>
            @if($notice->hasProva())
            <x-jet-dropdown-link href="{{ route('admin.notices.exam-resources-analysis.index',['notice'=>$notice]) }}">
              Recursos Realização de Prova
            </x-jet-dropdown-link>
            <x-jet-dropdown-link href="{{ route('admin.notices.additional-test-time.index',['notice'=>$notice]) }}">
              Tempo Adicional de Prova
            </x-jet-dropdown-link>
            @can('allowAllocateExamRoom',$notice)
                <x-jet-dropdown-link href="{{ route('admin.notices.allocation-of-exam-room.index',['notice'=>$notice]) }}">
                    Ensalamento
                </x-jet-dropdown-link>
            @endcan
            @endif
            <x-jet-dropdown-link href="{{ route('admin.notices.mail-send.edit',['notice'=>$notice]) }}">
              Notificar por E-mail
            </x-jet-dropdown-link>
            @endcan
            @can('isAcademicRegister')
            <x-jet-dropdown-link href="{{ route('cra.notices.subscriptions.index',['notice'=>$notice]) }}">
              Inscritos
            </x-jet-dropdown-link>
            @endcan
            @can('isAcademicRegisterOrPPICommitte')
            <x-jet-dropdown-link href="{{ route('cra.notices.subscriptions.ppi',['notice'=>$notice]) }}">
              Aferição PPI
            </x-jet-dropdown-link>
            @endcan
          </x-slot>
        </x-jet-dropdown>
      </li>
    </ul>
    @can('isAdmin')

    @if($notice->hasFee())
    <ul class="">
      <li
        class="block md:inline md:float-left sm:z-50 px-4 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
        <x-jet-dropdown align="left" width="48">
          <x-slot name="trigger">
            <a href="#" class="inline text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
              Financeiro
            </a>
            <svg class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </x-slot>

          <x-slot name="content">
            <x-jet-dropdown-link
              href="{{ route('admin.notices.read-gru-file.index', ['notice' => $notice]) }}">
              Homologação Automática
            </x-jet-dropdown-link>
            <x-jet-dropdown-link href="{{ route('admin.notices.pending-payments.report',['notice'=>$notice]) }}">
              Pagamentos Pendentes
            </x-jet-dropdown-link>
            <x-jet-dropdown-link href="{{ route('admin.notices.payment-exemptions.index',['notice'=>$notice]) }}">
              Solicitações de Isenção de Pagamento
            </x-jet-dropdown-link>
          </x-slot>

        </x-jet-dropdown>
      </li>
    </ul>
    @endif


    <ul class="">
      <li
        class="block md:inline md:float-left sm:z-50 px-4 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
        <x-jet-dropdown align="left" width="48">
          <x-slot name="trigger">
            <a href="#" class="inline text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
              Relatórios
            </a>
            <svg class="h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </x-slot>

          <x-slot name="content">
            @if($notice->enrollment_call_table_created)
            <x-jet-dropdown-link
              href="{{ route('admin.notices.registered-candidates.report', ['notice' => $notice]) }}">
              Candidatos Matriculados
            </x-jet-dropdown-link>
            <x-jet-dropdown-link
              href="{{ route('admin.notices.total-by-calls.report', ['notice' => $notice]) }}">
              Candidatos (Não) Matriculados - Total
            </x-jet-dropdown-link>
            @endif
            <x-jet-dropdown-link
              href="{{ route('admin.notices.candidates-with-social-name.report', ['notice' => $notice]) }}">
              Candidatos com Nome Social
            </x-jet-dropdown-link>
            <x-jet-dropdown-link
              href="{{ route('admin.notices.affirmative-actions-ppi.report', ['notice' => $notice]) }}">
              Convocados PPI
            </x-jet-dropdown-link>
            <x-jet-dropdown-link href="{{ route('admin.notices.contact.report', ['notice' => $notice]) }}">
              Contatos
            </x-jet-dropdown-link>
            <x-jet-dropdown-link href="{{ route('admin.notices.summary.report', ['notice' => $notice]) }}" target="_blank">
              Dados Gerais (Resumo do edital)
            </x-jet-dropdown-link>
            <x-jet-dropdown-link href="{{ route('admin.notices.candidates-address.report', ['notice' => $notice]) }}" target="_blank">
              Endereço dos Inscritos
            </x-jet-dropdown-link>
            <x-jet-dropdown-link
              href="{{ route('admin.notices.foreigns.report', ['notice' => $notice]) }}">
              Estrangeiros
            </x-jet-dropdown-link>
            <x-jet-dropdown-link
                href="{{ route('admin.notices.subscriptionsApproveds.report', ['notice' => $notice]) }}">
                Inscrições Homologadas
            </x-jet-dropdown-link>
            <x-jet-dropdown-link href="{{ route('admin.notices.totalSubscriptions.report', ['notice' => $notice]) }}">
              Inscritos e Homologados
            </x-jet-dropdown-link>
            @if($notice->hasEnem() or $notice->hasCurriculum() or $notice->hasSISU())
            <x-jet-dropdown-link
              href="{{ route('admin.notices.candidates-with-scores.report', ['notice' => $notice]) }}">
              Inscritos e Notas
            </x-jet-dropdown-link>
            @endif
            <x-jet-dropdown-link
              href="{{ route('admin.notices.totalBySelectionCriterias.report', ['notice' => $notice]) }}">
              Inscritos e Homologados por Crit. de Sel.
            </x-jet-dropdown-link>
            @can('lotteryDrawAvailable',$notice)
                <x-jet-dropdown-link
                href="{{ route('admin.notices.distributed-lottery-number.report', ['notice' => $notice]) }}">
                Números de Sorteio Distribuídos
                </x-jet-dropdown-link>
            @endcan

            @can('allowAllocateExamRoom',$notice)
                <x-jet-dropdown-link
                    href="{{ route('admin.notices.allocation-of-exam-room.report', ['notice' => $notice]) }}">
                    Relatório de Ensalamento
                </x-jet-dropdown-link>
            @endcan
            {{-- <form action="{{ route('admin.notices.allocation-of-exam-room.report', ['notice'=>$notice]) }}" method="POST">
                @csrf
                <button class="bg-orange-500 mt-3 hover:bg-orange-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Relatório de Ensalamento
                </button>
            </form> --}}

            <x-jet-dropdown-link
              href="{{ route('admin.notices.preliminaryClassificationRecourse.report', ['notice' => $notice]) }}">
              Recursos Classificação Preliminar
            </x-jet-dropdown-link>
            <x-jet-dropdown-link href="{{ route('admin.notices.check-ppi.report', ['notice' => $notice]) }}">
              Resultado da Aferição PPI
            </x-jet-dropdown-link>
            <x-jet-dropdown-link
              href="{{ route('admin.notices.candidatesForVacancies.report', ['notice' => $notice]) }}">
              Relação Candidato X Vagas
            </x-jet-dropdown-link>
            @if($notice->hasProva())
                <x-jet-dropdown-link
                    href="{{ route('admin.notices.payment-exemptions.report', ['notice' => $notice]) }}">
                    Solicitações de Isenção de pagamento
                </x-jet-dropdown-link>
                <x-jet-dropdown-link
                    href="{{ route('admin.notices.additional-test-time.report', ['notice' => $notice]) }}">
                    Solicitações de Tempo Adicional de Prova
                </x-jet-dropdown-link>
            <x-jet-dropdown-link
                href="{{ route('admin.notices.exam-resources-analysis.report', ['notice' => $notice]) }}">
                Solicitações de Recursos para Prova
            </x-jet-dropdown-link>
            @endif
            <x-jet-dropdown-link
              href="{{ route('admin.notices.totalCandidatesByCities.report', ['notice' => $notice]) }}">
              Total por Cidade
            </x-jet-dropdown-link>
          </x-slot>
        </x-jet-dropdown>
      </li>
    </ul>




    @endcan
    @endif
  </div>
</div>
@endif

<div class="container bg-white border-0 border-b-4 border-gray-300 rounded-md mx-auto px-4 my-5">
  <div class="grid grid-cols-6 gap-4">
    <div class="col-span-2 my-auto">
      <span class="text-xl md:text-2xl text-gray-400">{{$tip}}</span>
    </div>
    {{ $slot }}
  </div>
</div>
</div>
