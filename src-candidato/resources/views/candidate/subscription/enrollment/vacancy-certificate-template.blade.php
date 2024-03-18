<span class="text-2xl font-open-sans uppercase font-bold no-print">Atestado de Vaga para a inscrição {{
    $subscription->subscription_number }}</span>
<div class="grid grid-cols-12 gap-4 md:py-5 text-gray-600">
    <div class="col-span-12 md:col-span-12 border border-t-4 border-gray-400 rounded-md px-5 py-2">
        <div class="col-span-12 sm:col-span-6 md:col-span-6">
            <img class="w-52" src="{{asset('img/logo_ifc_h_color.png')}}" alt="{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}" />
        </div>
        <div class="col-span-12 sm:col-span-6 text-center md:col-span-6 md:text-right">
            <h2 class="text-sm md:text-lg font-bold uppercase pt-5">Edital {{ $subscription->notice->number }} </h2>
        </div>
        <div class="col-span-12 bg-gray-200 text-center">
            <h2 class="text-md uppercase">Atestado de vaga N° <b>{{ $subscription->getSubscriptionNumber()
                    }}</b>
            </h2>
        </div>

        <div class="col-span-12 px-5 py-5">
            <p>
                Atesto para os devidos fins e a quem interessar que há vaga no <b> Curso {{
                    $subscription->distributionOfVacancy->offer->courseCampusOffer->course->name }} </b>
                do {{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }} - <b> {{
                    $subscription->distributionOfVacancy->offer->courseCampusOffer->campus->name }}</b>, para o(a) aluno
                <b>
                    {{ $subscription->user->name }} </b>.
                A vaga está disponível até o dia <b> {{ $enrollmentSchedule->end_date->format('d/m/Y') }} </b> e até
                esta
                data o responsável legal deverá confirmar matrícula, apresentando todos os documentos necessários.
                Após este prazo, não mais haverá a garantia de vaga.
            </p>
        </div>
        <div class="col-span-12 px-5 py-5 text-sm text-right">
            <p>
                {{ $subscription->distributionOfVacancy->offer->courseCampusOffer->campus->city->name }}, {{
                $today->format('d/m/Y') }}
            </p>
            <p>Coordenação Geral de Avaliação e Ingresso - {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }}</p>
        </div>
        <div class="col-span-12 text-sm px-5 py-5">
            <p>Este documento foi gerado eletronicamente e pode ser verificado em {{ $url }}</p>
            <p>Você também pode verificar o documento apontando a câmera do dispositivo móvel para o QR Code</p>
            <p>Caso prefira, você pode copiar o link e enviar para quem solicitar!</p>
            {!! $subscription->getQrCodeVacancyCertificate($url) !!}
        </div>

    </div>
</div>