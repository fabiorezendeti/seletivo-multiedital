@extends('layouts.single')

@section('edital_name')
Edital Superior 01/2021
@endsection

@section('model_name')
Inscrições
@endsection

@section('content')
<x-jet-validation-errors class="mb-4" />

<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="inline-block min-w-full px-10  overflow-hidden">
        <div class="grid grid-cols-6 gap-4 py-5">
            <div class="col-span-6  ">
                <x-jet-label value="Seu Nome Completo" />
                <x-jet-input class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <div class="col-span-2">
                <x-jet-label value="Select" />
                <div class="relative">
                    <select class="form-input rounded-md shadow-sm w-full">
                        <option>Opçao 1</option>
                        <option>Opçao 2</option>
                        <option>Opçao 3</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-2 md:col-span-2 ">
                <x-jet-label value="Chekbox" />
                <input type="checkbox" id="ch1">
                <label for="ch1" class="inline font-medium text-md text-gray-700">opção 1 </label>
                <input type="checkbox" id="ch2">
                <label for="ch2" class="inline font-medium text-md text-gray-700">opção 2 </label>
            </div>

            <div class="col-span-6 sm:col-span-2 md:col-span-2 ">
                <x-jet-label value="Radio Buttons" />
                <p><input type="radio" id="rb1" name="drone" value="huey" checked>
                    <label for="rb1" class="font-medium text-md text-gray-700">Opção 1</label></p>
                <p><input type="radio" id="rb2" name="drone" value="huey" checked>
                    <label for="rb2" class="font-medium text-md text-gray-700">Opção 2</label></p>
            </div>



            <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                <x-jet-label value="CPF Ex: 023.456.789-10" />
                <x-jet-input class="block mt-1 w-full" type="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="000.000.000-45" name="cpf" :value="old('cpf')" required autofocus />
            </div>

            <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                <x-jet-label value="{{ __('RG') }}" />
                <x-jet-input class="block mt-1 w-full" type="text" name="rg" minlength="3" maxlength="20" :value="old('rg')" required />
            </div>

            <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                <x-jet-label value="{{ __('Emissor do RG') }}" />
                <x-jet-input class="block mt-1 w-full" type="text" name="rg_emmitter" minlength="4" maxlength="40" :value="old('rg_emmitter')" required />
            </div>

            <div class="col-span-6 ">
                <x-jet-label value="Nome completo da sua Mãe" />
                <x-jet-input class="block mt-1 w-full" type="text" name="mother_name" :value="old('mother_name')" />
            </div>

            <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                <x-jet-label value="{{ __('Password') }}" />
                <x-jet-input class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                <x-jet-label value="{{ __('Confirm Password') }}" />
                <x-jet-input class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">

            <a href="#" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                <span> Cadastrar</span>
            </a>



        </div>
    </div><!-- container -->
</form>
@endsection