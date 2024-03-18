@extends('admin.reports.html.template')

@section('content')
<div class="flex flex-col items-center">
    <x-report-header>
        <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
        <div class="text-sm font-bold">Recursos de Classificação Preliminar</div>
        <div class="divide-y"></div>
        <div class="text-sm font-bold">Campus {{$campus->name }}</div>
    </x-report-header>
    <table class="table-auto">
        <thead>
            <tr>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Inscrição
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Situação
                </th>
                <th
                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Resposta do Recurso
                </th>
            </tr>
        </thead>
        <tbody class="text-center uppercase">
            @foreach ($subscriptions as $subscription)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    {{$subscription->subscription_number}}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    @if($subscription->checkIfPreliminaryClassificationRecourseIsApproved())
                    <span class="text-green-500">Deferido</span>
                    @elseif($subscription->getPreliminaryClassificationRecourseFeedback())
                    <span class="text-red-500">Indeferido</span>
                    @else
                    <span class="text-gray-500">Pendente</span>
                    @endif
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    {!! nl2br(e($subscription->preliminary_classification_recourse['feedback']['feedback'] ?? null)) !!}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection