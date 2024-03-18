@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold">Edital {{ $notice->number.' - '.$notice->description }}</span>
<!--description-->
<p class="text-justify text-gray-600 text-xs md:text-sm"> {{ $notice->details }} </p>
<a href="{{ $notice->link }}"
  class="bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
  <svg class="fill-current w-2 h-2 md:w-4 md:h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
    fill="currentColor">
    <path fill-rule="evenodd"
      d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
      clip-rule="evenodd" />
  </svg> <span>Edital</span>
</a>


<div class="grid grid-cols-12 gap-4 md:py-5 text-gray-600">
  <div id="table-box" class="col-span-12 md:col-span-9 border border-t-4 border-gray-400 rounded-md px-5 py-2">
    <h2 class="text-lg font-bold uppercase"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
      Ofertas
    </h2>
    @include('candidate.subscription.subscription-exists-message')
    <div id="my-table" class="mt-5">
      <div class=" overflow-x-auto">
        <div class="inline-block min-w-full overflow-hidden">
          <table id="notice-offer-table" class="stripe hover">
            <thead>
              <tr>
                <th
                  class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Campus
                </th>
                <th
                  class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Curso
                </th>
                <th
                  class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Turno
                </th>
                <th
                  class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Vagas
                </th>
                <th
                  class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Ações
                </th>
              </tr>
            </thead>
            <tbody>
              <!--linha ini-->
              @foreach ($offers as $offer)
              <tr class="border-b-2 border-gray-200">
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-xs md:text-sm">
                  {{ $offer->courseCampusOffer->campus->name }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-xs md:text-sm">
                  {{ $offer->courseCampusOffer->course->name }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-xs md:text-sm">
                  {{ $offer->courseCampusOffer->shift->description }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-xs md:text-sm">
                  {{ $offer->total_vacancies }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-xs">
                  <a href="{{ $offer->courseCampusOffer->website }}" target="_blank"
                    class="bg-blue-700 hover:bg-blue-800 text-xs md:text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                    <svg class="fill-current w-2 h-2 md:w-4 md:h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2h-1.528A6 6 0 004 9.528V4z" />
                      <path fill-rule="evenodd"
                        d="M8 10a4 4 0 00-3.446 6.032l-1.261 1.26a1 1 0 101.414 1.415l1.261-1.261A4 4 0 108 10zm-2 4a2 2 0 114 0 2 2 0 01-4 0z"
                        clip-rule="evenodd" />
                    </svg>
                    <span>Detalhes</span>
                  </a>
                  <a href="{{ route('candidate.subscription.create',['notice'=>$notice,'offer'=>$offer]) }}"
                    class="bg-green-500 hover:bg-green-700 text-xs md:text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                    <svg class="fill-current w-2 h-2 md:w-4 md:h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    @if($subscription->id && $subscription->distributionOfVacancy->offer_id === $offer->id)
                      <span> Editar</span>
                    @else
                      <span> Selecionar</span>
                    @endif
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
  <!--fim table-box-->

  <div class="col-span-12 md:col-span-3">
    <div class="grid grid-cols-3 gap-4 text-gray-600">
      <div id="payment-box" class="col-span-3 border border-t-4 border-gray-400 rounded-md px-5 py-2">
        <h2 class="text-lg font-bold uppercase"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
          Taxas
        </h2>
        <p class="text-sm text-gray-500 mt-2"><b>Taxa de inscrição: </b>
          <span class="text-green-600">
            @if ($notice->hasFee())
            {{ number_format($notice->registration_fee, 2, ',','.') }}
            @else
            Gratuita
            @endif
          </span>
        </p>
        <p class="text-sm text-gray-500 mt-2"><b>Pagamento até: </b>
          <span class="text-green-600">
            @if ($notice->has_fee)
            {{ $notice->payment_date->format('d/m/Y') }}
            @else
            Não se aplica
            @endif
          </span>
        </p>
      </div>
      <!--fim payment-box-->

      <div id="dates-box" class="col-span-3 border border-t-4 border-gray-400 rounded-md px-5 py-2">
        <h2 class="text-lg font-bold uppercase"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
          Datas
        </h2>
        <p class="text-sm text-gray-500 mt-2"><b>Período de inscrição: </b></p>
        <span class="text-sm text-green-600 mb-2">
          {{ $notice->subscription_initial_date->format('d/m/Y') }} até
          {{ $notice->subscription_final_date->format('d/m/Y') }}
        </span>

        <p class="text-sm text-gray-500 mt-2"><b>Período de recursos de classificação: </b></p>
        <span class="text-sm text-green-600 mb-2">
          {{ $notice->classification_review_initial_date->format('d/m/Y') }} até
          {{ $notice->classification_review_final_date->format('d/m/Y') }}
        </span>
      </div>
      <!--fim dates-box-->
    </div>
  </div>




</div>

@endsection