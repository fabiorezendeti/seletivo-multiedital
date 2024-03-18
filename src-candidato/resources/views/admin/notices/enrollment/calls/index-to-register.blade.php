<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Matricular candidatos" :nomeEdital="$notice->number" :notice="$notice">
            @can('isAdmin')
            <x-manager.internal-navbar-itens tip="BUtton Tip" home="admin.notices.calls.index"
                :routeVars="['notice'=>$notice]" />
            @else
            <x-manager.internal-navbar-itens tip="BUtton Tip" home="cra.notices.calls.index"
            :routeVars="['notice'=>$notice]" />
            @endcan
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class="overflow-x-auto p-5">
            <div class="inline-block min-w-full overflow-hidden">

                <h2 class="text-2xl">Critério de Seleção : {{$selectionCriteria->description}}</h2>
                <form action="#" method="GET">
                    <div class="col-span-5 sm:col-span-5 md:col-span-5">
                        <label for="offer">Escolha uma oferta</label>
                        <x-select-box name="offer" id="offer">
                            <option value="">-- Todas --</option>
                            @foreach ($offers as $offer)
                            <option value="{{ $offer->id }}" @if($offer->id == request()->offer) selected="selected" @endif>{{ $offer->getString() }}</option>
                            @endforeach
                        </x-select-box>
                    </div>
                    <div class="col-span-5 sm:col-span-5 md:col-span-5">
                        <label for="affirmativeAction">Escolha uma ação afirmativa</label>
                        <x-select-box name="affirmativeAction" id="affirmativeAction">
                            <option value="">-- Todas --</option>
                            @foreach ($notice->modality->affirmativeActions->sortBy('slug') as $affirmative)
                            <option value="{{ $affirmative->id }}" @if(request()->input('affirmativeAction') == $affirmative->id) selected="selected" @endif
                                title="{{ $affirmative->description }}">{{ $affirmative->slug }}</option>
                            @endforeach
                        </x-select-box>
                    </div>
                    <div class="col-span-5 sm:col-span-5 md:col-span-5">
                        <label for="with-documents">Envio de documentos</label>
                        <x-select-box name="withDocuments" id="with-documents">
                            <option value="">-- Todos, independente de envio --</option>                            
                            <option value="Y" @if(request()->input('withDocuments') === 'Y') selected="selected" @endif
                                title="Arquivos enviados, aguardando análise">Arquivos enviados, aguardando análise</option>
                            <option value="OPEN" @if(request()->input('withDocuments') === 'OPEN') selected="selected" @endif
                                title="Em aberto, todos">Em aberto, todos</option>
                            <option value="OPEN_WITH_DOCS" @if(request()->input('withDocuments') === 'OPEN_WITH_DOCS') selected="selected" @endif
                                title="Em aberto, com alguns arquivos já enviados">Em aberto, com alguns arquivos já enviados</option>
                                <option value="OPEN_WITHOUT_DOCS" @if(request()->input('withDocuments') === 'OPEN_WITHOUT_DOCS') selected="selected" @endif
                                title="Em aberto, nenhum arquivo enviado">Em aberto, nenhum arquivo enviado</option>
                        </x-select-box>
                    </div>
                    <div class="col-span-5 sm:col-span-5 md:col-span-5">
                        <label for="status-search">Status</label>
                        <x-select-box name="status_search" id="status-search">
                            <option value="">-- Todos --</option>
                            <option value="pendente" @if(request()->input('status-search') == 'pendente') selected="selected" @endif>
                                Pendentes
                            </option>
                            <option value="matriculado" @if(request()->input('status-search') == 'matriculado') selected="selected" @endif>
                                Matriculados</option>
                            <option value="não matriculado" @if(request()->input('status-search') == 'não matriculado') selected="selected" @endif>
                                Não Matriculados</option>

                            <option value="pré cadastro" @if(request()->input('status-search') == 'pré cadastro') selected="selected" @endif>
                                Pré Cadastro</option>
                        </x-select-box>
                    </div>
                    <div class="col-span-5 sm:col-span-5 md:col-span-5">
                        <x-jet-label value="Texto para Busca" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="search" :value="old('search')"
                            placeholder="Buscar por Número de Inscrição, Nome ou CPF" value="{{ request()->search }}" />
                    </div>
                    <input type="hidden" class="form-checkbox h-8 w-8" name="selection_criteria_id"
                        value="{{ $selectionCriteria->id }}">
                    <x-jet-secondary-button type="submit">
                        Filtrar
                    </x-jet-secondary-button>
                    <x-jet-secondary-button type="submit" name="contact-report" value="1" formtarget="_blank">
                        Ver relatório de contatos
                    </x-jet-secondary-button>
                    <x-jet-secondary-button type="submit" name="contact-report-csv" value="1" formtarget="_blank">
                        Baixar relatório de contatos
                    </x-jet-secondary-button>
                </form>
            </div>
        </div>

        <div id="subscription-table" x-data="approved()">
            <div class=" overflow-x-auto">
                <div class="inline-block min-w-full  overflow-hidden">
                    <table class="min-w-full leading-normal manager-table">
                        <thead>
                            <tr>
                                <th>Inscrição</th>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Campus/Curso</th>
                                <th>Posição</th>
                                <th>Ação Afirmativa</th>
                                <th>Enviado?</th>
                                <th>Status</th>                                
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($approvedList as $approved)
                            <tr id="s{{ $approved->id }}">
                                <td>{{ $approved->subscription_number }}</td>
                                <td>{{ $approved->user->name }}</td>
                                <td>{{ $approved->user->cpf }}</td>
                                <td>{{ $approved->distributionOfVacancy->offer->courseCampusOffer->campus->name . ' - ' .
                                        $approved->distributionOfVacancy->offer->courseCampusOffer->course->name }}
                                </td>
                                <td>{{ $approved->call_position }}</td>
                                <td>{{ $approved->affirmative_action_slug }}</td>
                                <td>{{ $approved->send_at ? 'Sim' : 'Não' }}</td>
                                <td class="capitalize register-status">{{ ($approved->elimination) ? 'Desclassificado' : ucfirst($approved->status) }}</td>
                                <td>
                                    <button type="button" x-on:click="openModal({{$approved->call_id}})" class="blue-button">Ver</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $approvedList->withQueryString()->links() }}
            </div>

            <div x-show="open" id="modal" x-bind:class="{'opacity-0 pointer-events-none': open === false}"
                class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center">

                <div
                    class="modal-container bg-white w-11/12 mx-auto h-11/12 rounded shadow-lg  overflow-y-auto overscroll-auto z-50">
                    <div x-on:click="open = false"
                        class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-white text-sm z-50">
                        <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            viewBox="0 0 18 18">
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                            </path>
                        </svg>
                    </div>

                    <!-- Add margin if you want to see some of the overlay behind the modal-->
                    <div class="modal-content py-4 text-left z-10 px-3 overflow-y-auto h-full bg-white">
                        <!--Title-->
                        <div class="justify-between items-center pb-3">
                            <h2 class="text-xl text-white bg-blue-500 p-2 rounded" x-text="'Inscrição número: ' + approved.subscription_number"></h2>
                            <div class="modal-close cursor-pointer z-30" @click="open = false">
                                <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18"
                                    height="18" viewBox="0 0 18 18">
                                    <path
                                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 bg-white p-2 h-11/12 rounded">
                        <!--Body-->
                            <div class="mt-5">
                                <dl>
                                    <dt class="font-bold">Nome</dt>
                                    <dd x-text="approved.user.name"></dd>
                                    <dt class="font-bold">CPF</dt>
                                    <dd x-text="approved.user.cpf"></dd>
                                    <dt class="font-bold">Câmpus</dt>
                                    <dd x-text="approved.distribution_of_vacancy.offer.course_campus_offer.campus.name">
                                    </dd>
                                    <dt class="font-bold">Curso</dt>
                                    <dd x-text="approved.distribution_of_vacancy.offer.course_campus_offer.course.name">
                                    </dd>
                                    <dt class="font-bold">Status</dt>
                                    <dd x-show="approved.elimination">CANDIDATO ELIMINADO - NÃO MATRICULADO</dd>
                                    <x-select-box name="status" id="status" :required="true" x-show="!approved.elimination" x-model="approved.status">
                                        <option :selected="true">Selecione um Status</option>
                                        <option :selected="approved.status === 'pendente'" value="pendente">Pendente
                                        </option>
                                        <option :selected="approved.status === 'pré cadastro'" value="pré cadastro">
                                            Pré Cadastro</option>
                                        <option :selected="approved.status === 'matriculado'" value="matriculado">
                                            Matriculado</option>
                                        <option :selected="approved.status === 'não matriculado'" value="não matriculado">
                                            Não Matriculado</option>
                                    </x-select-box>
                                    <div x-show="enrollmentProcess.id" class="border bg-gray-100 rounded border-gray-500 p-2 mt-2">
                                        <h3 class="font-bold">Solicitação de matrícula</h3>
                                        <p x-show="enrollmentProcess.send_at_pt_br == null" class="text-red-700 font-bold">Processo não finalizado pelo candidato</p>
                                        <p class="text-sm">Enviado em <span x-text="enrollmentProcess.send_at_pt_br"></span></p>
                                        <div>
                                            <p class="text-sm">
                                                Caso a matrícula solicitada tenha problemas como ausência de envio de arquivos aponte aqui as
                                                inconsistência. Não use este campo para apontar problema com um arquivo enviado em específico.
                                            </p>                                                                                        
                                            <button x-on:click="download()" class="bg-gray-500 rounded text-white hover:bg-gray-400 p-2">
                                                 Baixar arquivos
                                            </button>
                                            <textarea x-model="enrollmentProcess.feedback" cols="30" rows="5"  class="border border-gray-500 w-full p-2"></textarea>
                                            <button x-on:click="feedbackSave()" class="bg-gray-500 rounded text-white hover:bg-gray-400 p-2">
                                                Emitir feedback
                                            </button>
                                        </div>
                                    </div>
                                    @can('isAdmin')
                                    <div x-show="!approved.elimination" class="flex border-red-700 border bg-red-100 p-4 mt-4">
                                        <form id="eliminar">
                                            <h2 class="font-bold">Eliminar</h2>
                                            <x-jet-label value="Motivo para eliminação" />
                                            <x-textarea class="w-full" name="reason" x-model="eliminateReason" />
                                            <x-jet-button type="submit" form="eliminar" x-on:click.prevent="eliminate"
                                                class="bg-red-700 hover:bg-red-300 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded"
                                                form="resource-feedback-form">Eliminar Candidato
                                            </x-jet-button>
                                        </form>
                                    </div>                                                                                                                
                                    @endcan
                                    <div x-show="enrollmentProcess.id"  class="flex border-green-700 border bg-green-100 p-4 mt-4">
                                        <form id="sign" target="_blank" action="" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <h2 class="font-bold">Assinar e baixar</h2>
                                            <p>Funcionalidade Experimental - assinará os documentos que conseguir, alguns por conflito não assina, porém identificará no 
                                                arquivo baixado para proceder com processo de assinatura manual
                                            </p>
                                            <x-jet-label value="Certificado Digital"/>
                                            <x-jet-input type="file" id="certificate" name="certificate" />
                                            <x-jet-label value="Senha do Certificado"/>
                                            <x-jet-input type="password" id="password" name="password"/>
                                            <x-jet-button type="submit" form="sign" x-on:click.prevent="sign"
                                                class="bg-green-700 hover:bg-green-300 text-white font-bold py-2 px-4 m-4 border-b-4 border-green-700 hover:border-green-500 rounded"
                                                >Assinar todos os documentos
                                            </x-jet-button>
                                        </form>
                                    </div>    
                                </dl>
                            </div>
                            <div class="p-2 m-2 border-l-2 border-t-2 border-gray-700 rounded col-span-2">
                                <h2 class="text-sm mb-2">Clique no documento para analisar</h2>
                                <ul>
                                    <template x-for="doc in documents">
                                        <li class="inline-block mt-4">
                                            <a :href="doc.url"
                                              x-on:click="showDocumentFeedback(doc.id)"
                                              target="doc-view" class="bg-blue-500 text-white p-2 rounded" x-text="doc.document_title"></a>
                                        </li>
                                    </template>
                                </ul>
                                <div x-show="documentViewShow">
                                    <h3 class="text-xl p-2" id="document-title"></h3>
                                    <p class="text-sm bg-red-300 p-2 border-red-700">
                                        Se o candidato precisa enviar novamente o documento ou algum problema existe com ele preencha o campo abaixo,
                                        caso o documento esteja correto marque a opção Sim no campo "o documento está correto?" e realize o Feedback
                                    </p>
                                    <input type="hidden" id="document-id" />
                                    <textarea name="document_feedback" x-show="!valid" id="document-feedback" class="w-full border p-2 rounded" cols="30" rows="5"></textarea>                                    
                                    <label class="block font-medium text-sm text-gray-700" for="is_foreign">
                                        O documento está correto?
                                    </label>
                                    <input type="checkbox" x-model="valid" class="form-checkbox h-8 w-8 text-green-500" name="is_valid" value="true" id="is-valid" /> Sim <br>                                    
                                    <button x-on:click="documentFeedbackSave()" class="bg-gray-500 rounded text-white hover:bg-gray-400 p-2 mt-4">
                                        Emitir feedback do documento
                                    </button>
                                </div>
                                <iframe width="100%" id="frame-docs"  frameborder="0" class="bg-white mt-2 w-full h-full" name="doc-view"></iframe>
                            </div>
                            <div class="pt-2 col-span-3">
                                <button type="button" x-on:click="submit()"
                                    class="modal-close bg-gray-500 hover:bg-gray-400 text-white font-bold py-1 px-4 m-4 border-b-4 border-gray-700 hover:border-gray-500 rounded">Submeter</button>
                                <button type="button" @click="open = false"
                                    class="modal-close bg-gray-500 hover:bg-gray-400 text-white font-bold py-1 px-4 m-4 border-b-4 border-gray-700 hover:border-gray-500 rounded">Fechar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')

    <script>
        function approved(){
            return {
                approved: {
                    user: {},
                    distribution_of_vacancy: {
                        offer: {
                            course_campus_offer: {
                                course: {},
                                campus: {}
                            }
                        }
                    }
                },
                documentViewShow: false,
                documents: [],
                enrollmentProcess: {},
                eliminateReason: null,
                open: false,
                valid: false,
                documentSearch: function(id) {
                    for (let i = 0; i < this.documents.length;i++) {
                        if (id == this.documents[i].id) return this.documents[i]
                    }
                },
                openModal: function(call_id) {
                    this.documentViewShow = false;
                    let frame = document.querySelector('#frame-docs');
                    frame.setAttribute('src','about:blank');
                    this.documents = [];
                    this.enrollmentProcess = {};
                    axios.get("approved/"+call_id, {
                        params: {
                            selection_criteria_id: {{ $selectionCriteria->id }}
                        }
                    })
                        .then((response)=>{
                            this.approved = response.data.subscription,
                            this.documents = response.data.documents ?? [],
                            this.enrollmentProcess = response.data.enrollmentProcess ?? {}
                            console.log(response.data)
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu')
                        })
                    this.open = true
                },
                feedbackSave: function() {
                    axios.post("enrollment-process/"+this.enrollmentProcess.id+"/feedback",{
                        feedback: this.enrollmentProcess.feedback
                    }).then( (response)=> {
                        this.enrollmentProcess.feedback = response.data.enrollmentProcess.feedback
                        window.alert("O feedback foi salvo com sucesso")
                    } )
                    .catch( (response)=> {
                        window.alert('Ops, deu um problema quando emitiu um feedback ao usuário')
                    } )
                },
                download: function() {
                    window.open("enrollment-process/"+this.enrollmentProcess.id+"/download-documents/"+this.approved.id,'_blank')
                },
                showDocumentFeedback: function(docId) {
                    this.documentViewShow = true;
                    let theDoc = this.documentSearch(docId);
                    let title = document.querySelector('#document-title');
                    title.innerHTML = "Analisando o documento " + theDoc.document_title;
                    document.querySelector('#document-feedback').value = theDoc.feedback ?? null;                                    
                    this.valid = theDoc.feedback_user_id && !theDoc.feedback;                    
                    document.querySelector('#document-id').value = theDoc.id;
                },
                documentFeedbackSave: function() {
                    let feedback = document.querySelector('#document-feedback').value;                                        
                    let documentId = document.querySelector('#document-id').value;                    
                    if (!this.valid && !feedback) {
                        window.alert("Você deve preencher o feedback ou marcar o campo SIM");
                        return;
                    }
                    axios.put("enrollment-process/"+this.enrollmentProcess.id+"/document/" + documentId, {
                        feedback: feedback,
                        is_valid: this.valid
                    }).then( (response)=> {
                        this.documents = response.data.documents
                        window.alert("O feedback foi salvo com sucesso")
                    } )
                    .catch( (response)=> {
                        window.alert('Ops, deu um problema quando emitiu um feedback ao usuário')
                    } )
                },
                submit: function() {
                    //window.alert("Valor chamado: " + document.getElementById('status').value);
                    axios.put("approved/"+this.approved.call_id+"/register",{
                        selection_criteria_id: {{ $selectionCriteria->id }},
                        status: document.getElementById('status').value
                    })
                        .then((response)=>{
                            console.log(response.data)
                            this.approved.status = response.data.status
                            statusField = document.querySelector("#s"+this.approved.id + " > .register-status")
                            statusField.innerText = response.data.status
                            // window.alert('Candidato atualizado com sucesso!')
                            this.open = false;
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu, nada foi alterado')
                        })

                },
                eliminate: function() {
                    axios.put("../../subscriptions/"+this.approved.id+"/eliminate",{
                        'reason': this.eliminateReason
                    })
                        .then((response)=>{
                            this.approved.elimination = response.data.elimination
                            statusField = document.querySelector("#s"+this.approved.id + " > .register-status")
                            statusField.innerText = "Desclassificado"
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu, nada foi alterado')
                        })
                },
                sign: function() {                    
                    let form = document.querySelector('#sign')
                    form.setAttribute('action',"enrollment-process/"+this.enrollmentProcess.id+"/sign-and-download/"+this.approved.id)
                    form.submit();
                }
            }
        }
    </script>

    @endpush
</x-manager.app-layout>
