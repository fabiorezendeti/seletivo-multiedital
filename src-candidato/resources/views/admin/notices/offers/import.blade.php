<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Importar Ofertas" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens tip="Editais" home="admin.notices.show" :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="grid grid-cols-12">
            <div class="px-5 py-5 overflow-hidden col-span-12">
                <h2 class="border-gray-700 border-b-2">Submeter Arquivo</h2>
                <x-jet-validation-errors class="mb-4" />
            </div>
            <div class="col-span-6 p-5">
                <div class="col-span-6 p-5 bg-red-200 text-red-700 rounded border border-red-700">
                    <h2 class="font-bold text-red-700">MUITA ATENÇÃO!</h2>
                    <p>
                        O formato do arquivo deve estar conforme o solicitado, leia as instruções no
                        <a href="#quadro-instrucoes">QUADRO DE INSTRUÇÕES</a>.
                        Antes de importar faça uma verificação do arquivo no seu computador para verificar os nomes das colunas e se ele é aberto
                        corretamente. Alguns casos podem desconfigurar o arquivo e causar problemas na importação. Não realize a importação, em hipótese alguma, 
                        sem realizar uma validação ou se constatar irregularidades.
                    </p>
                </div>
                <form id="form-notice" method="POST" enctype="multipart/form-data" action="{{ route('admin.notices.offers.import.store',['notice'=>$notice]) }}">
                    @csrf
                    <x-jet-label value="Selecione um arquivo CSV para importar as ofertas no processo" class="mt-4" />
                    <x-jet-input type="file" class="flex items-center justify-end mt-4" name="sisu_file" required="required" />
                    <button type="submit" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4
                            border-blue-700 hover:border-blue-500 rounded">
                        Importar
                    </button>
                </form>
            </div>
            <div class="col-span-6 p-5 bg-red-200 text-red-700 mt-4 rounded border border-red-700" id="quadro-instrucoes">
                <h2 class="font-bold text-red-700">QUADRO DE INSTRUÇÕES!</h2>
                <ul class="list">
                    <li>As colunas devem ser separadas por vírgula (,) e as strings com aspa dupla ("), a quebra de linha caracteriza um novo registro.</li>
                    <li>Não importa quantas colunas o arquivo tenha, nós buscaremos as seguintes colunas</li>
                    <li>Certifique que a primeira linha do arquivo corresponda ao cabeçalho dos dados.</li>
                </ul>
                <table>
                    <thead class="border-b border-red-700">
                        <th class="border-r border-red-700">Coluna no arquivo</th>
                        <th>Descrição</th>
                    </thead>
                    <tbody>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NO_CAMPUS</td>
                            <td>Nome do Campus</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NO_CURSO</td>
                            <td>Nome do Curso</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">CO_IES_CURSO</td>
                            <td>Código da IES do CURSO</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">DS_TURNO</td>
                            <td>Turno do Curso</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">QT_VAGAS_CONCORRENCIA</td>
                            <td>Quantidade de Vagas Concorrência</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NO_MODALIDADE_CONCORRENCIA</td>
                            <td>Ação Afirmativa (Modalidade Concorrência)</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">CO_INSCRICAO_ENEM</td>
                            <td>Número de inscrição do aluno no ENEM</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NO_INSCRITO</td>
                            <td>Nome do inscrito</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NO_SOCIAL</td>
                            <td>Nome Social do Inscrito</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_CPF_INSCRITO</td>
                            <td>Número do CPF do inscrito</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">DT_NASCIMENTO</td>
                            <td>Data de Nascimento no formato ANO-MES-DIA (HORA:MINUTO:SEGUNDO opcional)</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_RG</td>
                            <td>Número do RG do Inscrito</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NO_MAE</td>
                            <td>Nome da Mãe do inscrito</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">DS_LOGRADOURO</td>
                            <td>Rua do Candidato</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_ENDERECO</td>
                            <td>Nº da Residência</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">DS_COMPLEMENTO</td>
                            <td>Complemento do endereço</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">SG_UF_INSCRITO</td>
                            <td>Unidade Federativa - Estado</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NO_MUNICIPIO</td>
                            <td>Número do Município</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NO_BAIRRO</td>
                            <td>Bairro</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_CEP</td>
                            <td>CEP</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_FONE1</td>
                            <td>Telefone Principal</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_FONE2</td>
                            <td>Telefone Secundário</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">E-MAIL</td>
                            <td>DS_EMAIL</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_NOTA_L</td>
                            <td>Nota de Linguagens</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_NOTA_CH</td>
                            <td>Nota de Ciências Humanas</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_NOTA_CN</td>
                            <td>Nota de Ciências da Natureza</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_NOTA_M</td>
                            <td>Nota de matemática</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_NOTA_R</td>
                            <td>Nota de Redação</td>
                        </tr>
                        <tr class="border-b border-red-700">
                            <td class="border-r border-red-700">NU_NOTA_CANDIDATO</td>
                            <td>Média do Candidato</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>

</x-manager.app-layout>