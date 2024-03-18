@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold">Inscrição - Seleção por ENEM</span>

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
            @include('candidate.subscription.criteria-templates.enem')

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