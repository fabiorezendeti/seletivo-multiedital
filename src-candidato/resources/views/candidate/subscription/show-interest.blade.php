<div class="col-span-12  border bg-green-500 text-white px-10 py-10 text-sm no-print">
    <h2 class="text-lg font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-red-200 pl-4"></span>
        Manifestação de Interesse
    </h2>
    <div class="bg-white text-black p-4 rounded">        
        <p>Você está na lista de espera do SISU para ingressar no {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }}. Para continuar participando do nosso processo você deve manifestar o
            interesse pela vaga clicando no botão "QUERO CONCORRER A VAGA PELO SISU", caso opte por NÃO participar não é necessário fazer nada
        </p>
        <form action="{{ route('candidate.subscription.show-interest', $subscription) }}" method="POST">
            @csrf
            <x-jet-button type="submit" class="bg-green-700 hover:bg-green-500 mt-4">QUERO CONCORRER A VAGA PELO SISU</x-jet-button>
        </form>
    </div>
</div>