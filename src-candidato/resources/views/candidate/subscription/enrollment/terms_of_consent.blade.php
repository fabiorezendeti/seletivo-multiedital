@if(isset($hasPending) && !$subscription->acceptedTerms()) <input type="hidden" name="terms_of_consent" value="true"> @endif
<label for="term_of_authorization_image_use">1. TERMO DE USO DE IMAGEM</label>
<div class="bg-red-100 rounded border-red-700 border p-4 text-red-700">

    <label class="block items-center">
        <input type="checkbox" class="form-checkbox h-8 w-8 " required
               name="term_of_authorization_image_use" value="1" x-model="declaration">
        <span class="ml-2 ">AUTORIZO o uso de imagem em todo e qualquer material entre fotos e documentos, para ser utilizada em campanhas promocionais e institucional pelo {{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}, para serem essas destinadas à divulgação ao público em geral. A presente autorização é concedida a título gratuito, abrangendo o uso da imagem acima mencionada em todo território nacional e no exterior, das seguintes formas: (I) out-door; (II) busdoor; folhetos em geral (encartes, mala direta, catálogo, etc.); (III) folder de apresentação; (IV) anúncios em revistas e jornais em geral; (V) home page; (VI) cartazes; (VII) back-light; (VIII) mídia eletrônica (painéis, vídeo-tapes, televisão, cinema, programa para rádio, entre outros).</span>
        <x-jet-input-error for="term_of_authorization_image_use" class="mt-2" />
    </label>
</div>
<br>
<label for="term_of_responsibility_for_damage_caused">2. TERMO DE RESPONSABILIDADE POR DANOS CAUSADOS</label>
<div class="bg-red-100 rounded border-red-700 border p-4 text-red-700">

    <label class="block items-center">
        <input type="checkbox" class="form-checkbox h-8 w-8 " required
               name="term_of_responsibility_for_damage_caused" value="1" x-model="declaration">
        <span class="ml-2 ">PELO PRESENTE TERMO, declaro assumir total responsabilidade por qualquer dano que, aluno(a) do {{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}, vier a causar ao patrimônio da instituição por dolo ou culpa, devendo reparar financeiramente, tão logo forem feitas as apurações necessárias.</span>
        <x-jet-input-error for="term_of_responsibility_for_damage_caused" class="mt-2" />
    </label>
</div>
<br>
<label for="term_consent_of_regulation_student_conduct">3. TERMO DE CIÊNCIA - REGULAMENTO DE CONDUTA DISCENTE</label>
<div class="bg-red-100 rounded border-red-700 border p-4 text-red-700">

    <label class="block items-center">
        <input type="checkbox" class="form-checkbox h-8 w-8 " required
               name="term_consent_of_regulation_student_conduct" value="1" x-model="declaration">
        <span class="ml-2 ">O(A) discente e seu(sua) responsável legal entendem que tem o dever de “Conhecer, respeitar e cumprir os regulamentos, as normas, as diretrizes e as instruções relativas a quaisquer atividades relativas ao {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }}, desenvolvidas nos âmbitos interno e externo à instituição”.</span>
        <x-jet-input-error for="term_consent_of_regulation_student_conduct" class="mt-2" />
    </label>
</div>
<br>
<label for="term_of_authorization_for_tours_and_trips">4. TERMO DE AUTORIZAÇÃO - SAÍDAS/PASSEIOS/VIAGENS DE ESTUDOS</label>
<div class="bg-red-100 rounded border-red-700 border p-4 text-red-700">

    <label class="block items-center">
        <input type="checkbox" class="form-checkbox h-8 w-8 " required
               name="term_of_authorization_for_tours_and_trips" value="1" x-model="declaration">
        <span class="ml-2 ">Autorizo o estudante ora matriculado a participar de saídas/passeios/viagens de estudos organizadas pelo {{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}.</span>
        <x-jet-input-error for="term_of_authorization_for_tours_and_trips" class="mt-2" />
    </label>
</div>
<br>
<label for="term_of_veracity_of_information_provided">5. TERMO DE VERACIDADE DAS INFORMAÇÕES</label>
<div class="bg-red-100 rounded border-red-700 border p-4 text-red-700">

    <label class="block items-center">
        <input type="checkbox" class="form-checkbox h-8 w-8 " required
               name="term_of_veracity_of_information_provided" value="1" x-model="declaration">
        <span class="ml-2 ">Declaro sob as penas da lei, que os documentos apresentados digitalizados, sem possibilidade de validação digital, ao presente protocolo de matrícula dos processos seletivos do {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }} são verdadeiros e conferem com os respectivos originais, assim como a veracidade das informações presentes nos formulários e declarações enviados</span>
        <x-jet-input-error for="term_of_veracity_of_information_provided" class="mt-2" />
    </label>
</div>
@if($subscription->notice->modality->id == 1 /** Apenas para edital da modalidade Técnico Integrado ao Ensino Médio */)
<br>
<label for="term_of_consent_to_foreign_language_placement_test">6. TERMO DE CIÊNCIA - TESTES DE NIVELAMENTO EM LÍNGUAS ESTRANGEIRAS</label>
<div class="bg-red-100 rounded border-red-700 border p-4 text-red-700">

    <label class="block items-center">
        <input type="checkbox" class="form-checkbox h-8 w-8 " required
               name="term_of_consent_to_foreign_language_placement_test" value="1" x-model="declaration">
        <span class="ml-2 ">Em atendimento às Diretrizes para a Educação Profissional Técnica Integrada ao Ensino Médio do {{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }} que prevê a oferta de Língua Inglesa, Língua Espanhola e Libras por níveis de proficiência, em articulação com o Centro de Línguas do {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }}, a partir do ano letivo de 2020, declaro estar ciente de que o(a) estudante, necessitará realizar testes de nivelamento ao ingressar nesta Instituição de Ensino, e que, dependendo do seu desempenho, poderá obter o aproveitamento dessas disciplinas (Inglês, Espanhol e/ou Libras) que integram a matriz curricular do seu curso, em seu histórico escolar. Nesse caso, estou ciente de que o(a) estudante deverá utilizar a carga horária disponível em seu horário acadêmico semanal, decorrente do possível aproveitamento das disciplinas acima mencionadas, para dedicar-se a outras atividades acadêmicas ofertadas pela Instituição.</span>
        <x-jet-input-error for="term_of_consent_to_foreign_language_placement_test" class="mt-2" />
    </label>
</div>
@endif
