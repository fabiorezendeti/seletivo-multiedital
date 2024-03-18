@extends('layouts.candidate.app')
@section('content-app') 
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    Você precisa manter seus dados de contato sempre atualizados
    <x-success-message />
    <x-jet-validation-errors class="mb-4" />
    <form action="{{route('user.contact.update')}}" method="POST">
        @csrf
        @method('put')
        <livewire:contact />
        <button class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-green-700 hover:border-green-500 rounded">
            <span>Atualizar</span>
        </button>
    </form>
</div>

@endsection