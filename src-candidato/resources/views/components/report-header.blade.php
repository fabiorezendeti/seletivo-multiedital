<div class="flex flex-col items-center w-full border-b-2">
    <div><img src="{{ asset('/images/republica_brasao_cor_rgb.jpg') }}" class="w-16" alt="Brasão da República">
    </div>
    <div class="uppercase text-sm">MINISTÉRIO DA EDUCAÇÃO</div>
    <div class="uppercase text-sm">SECRETARIA DE EDUCAÇÃO PROFISSIONAL E TECNOLÓGICA</div>
    <div class="uppercase text-sm">{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }} </div>
    {{ $slot }}
</div>