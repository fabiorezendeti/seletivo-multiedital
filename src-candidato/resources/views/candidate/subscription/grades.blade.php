@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold">Inscrição - Seleção por Análise de Currículo</span>

<div class="grid grid-cols-12 gap-4 md:py-5 text-gray-600">
    <div class="col-span-12 md:col-span-6 border border-t-4 border-green-700 rounded-md px-5 py-2 leading-loose">
        <h2 class="text-md font-bold uppercase mb-3 text-green-700"><span class="borde-0 border-l-2 border-green-400 pl-4"></span>
            Dados da inscrição
        </h2>

        <p>Você está se inscrevendo para o Processo Seletivo:</p>
        <p><b>EDITAL:</b> <span>01/2020</span></p>
        <p><b>CURSO:</b> <span>Técnico em Agrimensura</span></p>
        <p><b>Campus Avançado Abelardo Luz</b></p>

        <form>
            <div class="grid grid-cols-12 md:py-5 text-gray-600">
                <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
                    <span class="text-6xl text-gray-200">4</span>
                </div>

                <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
                    <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                        Informe a modalidade pela qual se deu a conclusão do ensino médio:
                    </h2>
                                        
                    <label class="block items-center">
                        <input id="check-modality" type="radio" class="form-radio h-8 w-8 text-green-500" name="opt-modality" value="0">
                        <span class="ml-4 text-lg">Ensino Médio Regular</span>
                    </label>
                    <label class="block items-center">
                        <input id="check-modality" type="radio" class="form-radio h-8 w-8 text-green-500" name="opt-modality" value="1">
                        <span class="ml-4 text-lg">Ensino Médio Técnico</span>
                    </label>
                    <label class="block items-center">
                        <input id="check-modality" type="radio" class="form-radio h-8 w-8 text-green-500" name="opt-modality" value="2">
                        <span class="ml-4 text-lg">Enem</span>
                    </label>
                    <label class="block items-center">
                        <input id="check-modality" type="radio" class="form-radio h-8 w-8 text-green-500" name="opt-modality" value="3">
                        <span class="ml-4 text-lg">Encceja</span>
                    </label>
                </div>
                
            </div>

            <div id="modality-grades" class="hidden grid grid-cols-12 md:py-5 text-gray-600">
                <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
                    <span class="text-6xl text-gray-200">5</span>
                </div>

                <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
                    <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                        Informe suas notas nas seguintes áreas:
                    </h2>
                                        
                    <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                        <x-jet-label value="Linguagens, Códigos e suas Tecnologias:" />
                        <x-jet-input class="input-grade1 grade block mt-1 w-40" type="number" name="LCT" required autofocus />
                    </div>

                    <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                        <x-jet-label value="Matemática e suas Tecnologias:" />
                        <x-jet-input class="input-grade2 grade block mt-1 w-40" type="number" name="MT" required autofocus />
                    </div>

                    <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                        <x-jet-label value="Ciências Humanas e suas Tecnologias:" />
                        <x-jet-input class="input-grade3 grade block mt-1 w-40" type="number" name="CHT" required autofocus />
                    </div>

                    <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                        <x-jet-label value="Ciências da Natureza e suas Tecnologias:" />
                        <x-jet-input class="input-grade4 grade block mt-1 w-40" type="number" name="CNT" required autofocus />
                    </div>

                    <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                        <x-jet-label value="Média:" />
                        <x-jet-input id="average" disabled class="block mt-1 w-40" type="text" required autofocus value=0 />
                    </div>
                    
                </div>
            </div>

            <div id="modality-technician" class="hidden  grid grid-cols-12 md:py-5 text-gray-600">
                <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
                    <span class="text-6xl text-gray-200">5</span>
                </div>

                <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
                    <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                        Informe sua média geral obtida no ensino médio:
                    </h2>
                                        
                    <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                        <x-jet-label value="Média Geral:" />
                        <x-jet-input class="block mt-1 w-40" type="number" name="average" required autofocus />
                    </div>

                    
                </div>
            </div>

            <div id="upload-field" class="hidden  grid grid-cols-12 md:py-5 text-gray-600">
                <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
                    <span class="text-6xl text-gray-200">6</span>
                </div>

                <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
                    <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                        Envie o arquivo de boletim oficial de notas:
                    </h2>

                    <x-jet-label value="Boletim:" />
                    <x-jet-input class="block mt-1 w-full" type="file" name="enemUpload" required autofocus />
                                      
                </div>
            </div>

            <div>
                <a href="/candidate/subscription" class="bg-orange-500 hover:bg-orange-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd" />
                      </svg>
                    <span class="uppercase">Voltar</span>
                </a>
                <a href="#" class="float-right bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                      </svg>
                    <span class="uppercase">Finalizar inscrição</span>
                </a>
            </div>
    
        </form>
    </div>
        
</div>
@endsection

@push('js')
<script>
    $(function() {
        $("input").on("change", function() {
            if($('input[name=opt-modality]:checked').val() > 1){
                $("#modality-technician").hide();
                $("#modality-grades").css('display', 'flex');                
            }else{
                $("#modality-technician").css('display', 'flex');
                $("#modality-grades").hide();
            }
            $("#upload-field").css('display', 'flex');
        });

        $(".input-grade1, .input-grade2, .input-grade3, .input-grade4").change(function() {
            calcAverage();
        });

        function calcAverage(){
            var sum = parseFloat("0.0");
            $(".grade").each(function() {
                if($(this).val()>0){
                    sum += parseFloat($(this).val());  
                }
            });
            $("#average").val(parseFloat(sum/$(".grade").length));
        }
    });
</script>
@endpush