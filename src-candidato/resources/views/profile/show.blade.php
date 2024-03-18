@extends('layouts.candidate.app')
@section('content-app')
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('profile.update-profile-information-form')

            <x-jet-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('profile.update-password-form')
            </div>

            @if (!env('LOGIN_UNICO_ENABLE') && Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <x-jet-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>
            @endif

            @if (!env('LOGIN_UNICO_ENABLE'))
                <x-jet-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>
            @endif

            <x-jet-section-border />

            <div class="mt-10 sm:mt-0">
                @can('deleteUser',auth()->user())
                    @livewire('profile.delete-user-form')
                @endcan
            </div>
        </div>
    </div>
@endsection
