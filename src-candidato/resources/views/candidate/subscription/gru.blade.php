    <form method="POST" action="http://consulta.tesouro.fazenda.gov.br/gru_novosite/gerarHTML.asp">
        <p>Poderá efetuar o pagamento da taxa de inscrição utilizando GRU (Guia de Recolhimento da União), ao clicar em 'Gerar a GRU - Boleto' será
            aberta uma nova aba do navegador com o boleto a ser impresso, ou poderá solicitar a isenção da taxa de inscrição até 15 dias antes do
            encerramento das inscrições, clicando em 'Isenção de Pagamento' e preenchendo com os dados necessários.</p>
        {{-- <p>A taxa de inscrição é paga utilizando GRU (Guia de Recolhimento da União), ao clicar em Gerar a GRU será
            aberta uma nova aba do navegador</p> --}}
        <input name="codigo_favorecido" type="hidden" id="codigo_favorecido"
            value="{{ $subscription->notice->gru_config['codigo_favorecido'] }}">
        <input name="gestao" type="hidden" id="gestao" value="{{ $subscription->notice->gru_config['gestao'] }}">
        <input name="codigo_correlacao" type="hidden" id="codigo_correlacao"
            value="{{ $subscription->notice->gru_config['codigo_correlacao'] }}">
        <input name="nome_favorecido" type="hidden" id="nome_favorecido"
            value="{{ $subscription->notice->gru_config['nome_favorecido'] }}">
        <input name="codigo_recolhimento" type="hidden" id="codigo_recolhimento"
            value="{{ $subscription->notice->gru_config['codigo_recolhimento'] }}">
        <input name="nome_recolhimento" type="hidden" id="nome_recolhimento"
            value="{{ $subscription->notice->gru_config['nome_recolhimento'] }}">
        <input name="competencia" type="hidden" id="competencia"
            value="{{ $subscription->notice->gru_config['competencia'] }}">
        <input name="vencimento" type="hidden" id="vencimento"
            value="{{ $subscription->notice->payment_date->format('d/m/Y') }}">
        <input type="hidden" id="referencia" name="referencia" value="{{ $subscription->subscription_number }}">
        <input type="hidden" name="cnpj_cpf" id="cnpj_cpf" value="{{ $subscription->user->cpf }}">
        <input type="hidden" id="nome_contribuinte" name="nome_contribuinte" value="{{ $subscription->user->name }}">
        <input type="hidden" name="valorPrincipal" id="valorPrincipal"
            value="{{ $subscription->notice->registration_fee }}">
        <input type="hidden" name="descontos" id="descontos" value="">
        <input type="hidden" name="deducoes" id="deducoes" value="">
        <input type="hidden" name="multa" id="multa" value="">
        <input type="hidden" name="juros" id="juros" value="">
        <input type="hidden" name="acrescimos" id="acrescimos" value="">
        <input type="hidden" name="boleto" value="1">
        <input type="hidden" name="impressao" value="SA">
        <input type="hidden" name="pagamento" value="1">
        <input type="hidden" name="campo" value="CR">
        <input type="hidden" name="ind" value="0">

        <button type="submit" formtarget="_blank"
            formaction="http://consulta.tesouro.fazenda.gov.br/gru_novosite/gerarHTML.asp"
            class="bg-red-500 hover:bg-red-400 hover:text-red-700 text-lg  text-white no-print font-bold py-2 px-3 mt-5  rounded-md inline-flex  my-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="ml-2">Gerar GRU - Boleto</span>
        </button>
        
    </form>