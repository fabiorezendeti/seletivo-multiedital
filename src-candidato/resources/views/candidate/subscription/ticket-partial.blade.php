<div class="grid grid-cols-12 gap-4 md:py-5 text-gray-6000" id="print-area">    
    @if($subscription->notice->hasFee() && $subscription->is_homologated == null)
        @include('candidate.subscription.pagtesouro')
    @endif
    @can('allowShowInterest',$subscription)
        @include('candidate.subscription.show-interest')
    @endcan
    <div class="col-span-12 sm:col-span-6 md:col-span-6">
        <img class="w-52" src="{{asset('img/logo_ifc_h_color.png')}}" alt="{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}" />
    </div>
    <div class="col-span-12 sm:col-span-6 text-center md:col-span-6 md:text-right">
        <h2 class="text-sm md:text-lg font-bold uppercase pt-5">Edital {{ $subscription->notice->number }}
        <a href="{{ $subscription->notice->link }}"
                            class="no-print bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                            <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                    clip-rule="evenodd" />
                            </svg> <span>Edital</span>
                        </a></h2>
    </div>

    <div class="col-span-12 bg-gray-200 text-center">
        <h2 class="text-md uppercase">Comprovante de Inscrição N° <b class="subscription-number">{{ $subscription->getSubscriptionNumber() }}</b>
        </h2>
    </div>

    <div class="col-span-12  border border-gray-400 px-5 py-5 text-sm">
        <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
            Dados do candidato
        </h2>
        <p><b>Nome: </b> {{ $subscription->user->name }}</p>
        @if($subscription->user->social_name)
        <p><b>Nome Social: </b> {{ $subscription->user->social_name }}</p>
        @endif
        <p><b>Nome da Mãe: </b> {{ $subscription->user->mother_name }}</p>
        <p><b>CPF: </b><span>{{ $subscription->user->cpf }}</span></p>
        <p><b>RG: </b><span>{{ $subscription->user->rg }} - {{ $subscription->user->rg_emmitter }}</span></p>
        <p><b>Data de Nascimento: </b> {{ $subscription->user->birth_date->format('d/m/Y') }}</p>
        <p><b>E-mail: </b> {{ $subscription->user->email }}</p>
        <a href="{{ url('/user/profile') }}"
            class="bg-blue-500 hover:bg-blue-700 text-sm text-white no-print font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
            <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                    clip-rule="evenodd" />
            </svg> <span>Atualizar seu perfil</span>
        </a>
    </div>


    <div class="col-span-12 md:col-span-12 border border-gray-400 px-5 py-5 text-sm">
        <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
            Dados da inscrição
        </h2>

        <p><b>EDITAL:</b> <span>{{ $subscription->notice->number }}</span></p>
        <p><b>CURSO:</b> <span>{{ $subscription->distributionOfVacancy->offer->courseCampusOffer->course->name }}</span>
            <p><b>Homologado:</b> {{ $subscription->is_homologated === true ? 'Homologado' : 'Ainda não verificado' }}
                <span></span>
            </p>
            <p><b>Campus {{ $subscription->distributionOfVacancy->offer->courseCampusOffer->campus->name }}</b></p>
            <p><b>Critério de seleção:</b>
                <span>{{ $subscription->distributionOfVacancy->selectionCriteria->details }}</span></p>
            <p><b>Ação afirmativa:</b>
                <span>{{ $subscription->distributionOfVacancy->affirmativeAction->description }}</span></p>
            @if($subscription->getScore())
            <div class="border p-2">
                @if(isset($subscription->getScore()->modalidade))
                <p><b>Modalidade</b>
                    <span>{{ $subscription->getModalitiesForCurriculumAnalisysByTitle($subscription->getScore()->modalidade)->description }}
                    </span>
                </p>
                @endif
                @if (($subscription->distributionOfVacancy->isEnem() or
                $subscription->distributionOfVacancy->isCurriculum() or 
                $subscription->distributionOfVacancy->isSISU()
                ) &&
                !$subscription->checkIfScoreHasOnlyAverageByModality($subscription->getScore()->modalidade ?? null))
                <p><b> Linguagens, códigos e tecnologias </b>
                    <span>{{ $subscription->getScore()->linguagens_codigos_e_tecnologias ?? '--' }}</span></p>
                <p><b> Matemática e Suas Tecnologias </b>
                    <span>{{ $subscription->getScore()->matematica_e_suas_tecnologias ?? '--' }}</span></p>
                <p><b> Ciências humanas e suas tecnologias </b>
                    <span>{{ $subscription->getScore()->ciencias_humanas_e_suas_tecnologias ?? '--' }}</span></p>
                <p><b> Ciências da natureza e suas tecnologias</b>
                    <span>{{ $subscription->getScore()->ciencias_da_natureza_e_suas_tecnologias ?? '--' }}</span></p>
                @if ($subscription->distributionOfVacancy->isEnem() or $subscription->distributionOfVacancy->isSISU())
                <p><b>Redação</b>
                    <span>{{ $subscription->getScore()->redacao ?? '--'  }}
                    </span></p>
                <p><b>Ano de realização do ENEM</b>
                    <span>{{ $subscription->getScore()->ano_do_enem ?? '--' }}
                    </span></p>
                @endif
                @endif
                <p><b>Média de nota:</b>
                    <span>{{ $subscription->getScore()->media ?? 'Não informado' }}</span></p>
            </div>
            @endif
            @if( $subscription->hasSupportingDocuments() )
            <p class="no-print"><b>Boletim de Notas: </b>
                <span>
                    <a href="{{route('candidate.viewBoletim',['subscription'=>$subscription])}}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-4 rounded inline-flex items-center"
                        target="_blank">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" /></svg> <span>Visualizar</span>
                    </a>
                </span></p>
            @endif
            @if( $subscription->distributionOfVacancy->isProva() )
            <p><b>Necessidade Específica: </b>{{ $subscription->specialNeed->description ?? 'Nenhuma' }}</p>
            <p><b>Descrição da Necessidade Específica:</b> {{ $subscription->special_need_description ?? '--'}}</p>
            <p><b>Tempo Adicional de Prova:</b> {{ $subscription->additional_test_time ? 'Sim' : 'Não' }}
                - <b>Deferido:</b>   {{ $subscription->additional_test_time_analysis['approved_ptBR'] ?? 'Em análise' }}
            </p>
            <p><b>Recurso para realização de prova: </b>{{ $subscription->examResource->description ?? 'Nenhum' }} -  {{ $subscription->exam_resource_description ?? ''}}
            - <b>Deferido: </b>{{ $subscription->exam_resource_analysis['approved_ptBR'] ?? 'Em análise' }}</span>
            </p>
            @endif
            <p class="text-xs mt-5 italic"><b>Inscrição realizada em:</b>
                <span>{{ $subscription->created_at->format('d/m/Y H:i') }}</span>
                <b>Última alteração em:</b> <span>{{ $subscription->updated_at->format('d/m/Y H:i') }}</span></p>
            @can('subscriptionsIsOpen',$subscription->notice)
            <a href="{{ route('notice.show', ['notice' => $subscription->notice]) }}"
                class="bg-red-500 hover:bg-red-700 text-sm text-white no-print font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                        clip-rule="evenodd" />
                </svg> <span>Alterar inscrição</span>
            </a>
            @endcan
    </div>
    
    @can('examRoomAvailable', $subscription->notice)
        <div class="col-span-12  border border-gray-400 px-5 py-5 text-sm">
            <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
                Local de Prova
            </h2>
            <p><b>Local de Prova: </b> {{ $subscription->examRoomBooking->examLocation->local_name ?? null }}</p>
            <p><b>Endereço: </b> {{ ($subscription->examRoomBooking) ? $subscription->examRoomBooking->examLocation->getAddressString() : null }}</p>
            <p><b>Sala de Prova: </b> {{ $subscription->examRoomBooking->name ?? null }}</p>         
            <p><b>Data da Prova: </b> {{ $subscription->notice->exam_date->format('d/m/Y') ?? null }}</p>   
            <p><b>Horário da Prova: </b> {{ $subscription->notice->exam_time ?? null }}</p>   
        </div>
    @endcan
    

    @if( $subscription->hasSupportingDocuments() )
    <div class="col-span-12 md:col-span-12 border border-gray-400 px-5 py-5 text-sm no-print">
        <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
            Boletim de Notas
        </h2>
        <div style="height: 400px">
            <iframe src="{{route('candidate.viewBoletim',['subscription'=>$subscription])}}" width="100%" height="100%"
                frameborder="1"></iframe>
        </div>
    </div>
    @endif

    @if($subscription->hasPaymentExemptionDocuments())
        <div class="col-span-12  border border-gray-400 px-5 py-5 text-sm">
            <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
                Visualização de solicitação de isenção da taxa de inscrição
            </h2>
            <p><b>Documento RG - Frente: </b>
                <a href="{{ route('candidate.subscription.payment-exemption.viewDocumentIdFront', ['subscription'=>$subscription]) }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-4 rounded inline-flex items-center"
                    target="_blank">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" /></svg>
                    <span>Visualizar</span>
                </a>
            </p>
            <p><b>Documento RG - Verso: </b>
                <a href="{{ route('candidate.subscription.payment-exemption.viewDocumentIdBack', ['subscription'=>$subscription]) }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-4 rounded inline-flex items-center"
                    target="_blank">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" /></svg>
                    <span>Visualizar</span>
                </a>
            </p>
            <p><b>Formulário: </b>
                <a href="{{ route('candidate.subscription.payment-exemption.viewDocumentForm', ['subscription'=>$subscription]) }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-4 rounded inline-flex items-center"
                    target="_blank">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" /></svg>
                    <span>Visualizar</span>
                </a>
            </p>
        </div>
    @endif

    <div class="col-span-12 ">
        {!! $subscription->getQrCode() !!}
    </div>

</div>

@push('js')
<script defer="defer">
    window.onload = function(){
        let libras = document.querySelector('[vw]')
        libras.setAttribute("class",'no-print')
        let barraBrasil = document.querySelector('#barra-brasil')
        barraBrasil.setAttribute("class",'no-print')        
    };
</script>
@endpush

@push('css')
<style>
    @media print {
        html {
            background: none !important;
        }

        @page {
            size: portrait !important;
        }

        * {
            margin: 0px !important;
            padding: 0px !important;
        }

        body {
            width: 21cm !important;
            min-height: 29.7cm !important;
            margin: 1.5cm !important;
        }

        .justify-between {
            justify-content: normal !important;
        }

        body * {
            visibility: hidden;

        }

        #print-area {
            width: 100%;
            height: 100%;
            margin: 0px !important;
            padding: 0px !important;
        }

        #print-area * {
            visibility: visible;
        }

        @media print {

            #barra-brasil,
            #ally,
            #ally * {
                padding: 0 !important;
                margin: 0 !important;
                width: 0 !important;
                height: 0 !important;
            }
        }
    }
</style>
@endpush
