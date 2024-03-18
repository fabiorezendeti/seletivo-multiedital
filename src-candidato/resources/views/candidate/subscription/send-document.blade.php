<div x-bind:class="{'hidden': criteria !== 3 && criteria !== 4}" class="hidden grid grid-cols-12 md:py-5 text-gray-600">
    <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
        <span class="text-6xl text-gray-200">5</span>
    </div>

    <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
        <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                class="borde-0 border-l-2 border-blue-400 pl-4"></span>
            Envie o arquivo de boletim oficial de notas:
        </h2>
        <x-jet-label value="Boletim:" />
        @if($subscription->hasSupportingDocuments())
        <p class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
            x-show="criteria > 2 && (hasDocs === false || criteria === criteriaOld)">
            Você já enviou um documento de comprovação para
            o critério de seleção
            {{ $subscription->distributionOfVacancy->selectionCriteria->description }}
            , caso envie outro documento, o antigo
            será substituído. Garanta que o documento de comprovação é compatível
            o solicitado no Edital
        </p>
        @endif
        <x-jet-input x-bind:required="criteria > 2 && (hasDocs === false || criteria !== criteriaOld)"
            class="block mt-1 w-full" type="file" accept="application/pdf,image/jpeg,image/png"
            name="documento_comprovacao" />
        <x-jet-input-error for="documento_comprovacao" class="mt-2" />
        <p class="text-xs">O arquivo deve ter no máximo {{$uploadMaxSize}} MB de tamanho e deve estar nos formatos pdf,
            jpeg ou png </p>
    </div>
</div>