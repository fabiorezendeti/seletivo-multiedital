@extends('layouts.candidate.app')
@section('content-app')
    <x-success-message />
    <x-error-message />
    <span class="text-2xl font-open-sans uppercase font-bold">Aceite os termos do Edital {{ $subscription->notice->number}} para prosseguir:</span>
<form action="{{ route('candidate.subscription.enrollment-process.store',['subscription'=>$subscription]) }}"
      id="enrollment-form" method="POST">
    @csrf
    <br>
    @include('candidate.subscription.enrollment.terms_of_consent')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <button type="submit" x-on:click="submit" x-show="!hasPending" class="float-right bg-green-500 hover:bg-green-700 text-2xl text-white font-bold py-2 px-3 mt-10 rounded-md inline-flex items-center my-1 w-full" id="send-form">
                <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="uppercase">Aceitar e continuar</span>
            </button>
        </div>
    </div>
</form>
@endsection
