<x-jet-action-section>
    <x-slot name="title">
        Autenticação de dois fatores
    </x-slot>

    <x-slot name="description">
        Adicionando este tipo de autenticação você aumenta a segurança do seu login, não faça isso se você não tiver certeza de como funciona
    </x-slot>

    <x-slot name="content">
        <h3 class="text-lg font-medium text-gray-900">
            @if ($this->enabled)
                A autenticação de dois fatores está habilitada
            @else
                A autenticação de dois fatores não está habilitada
            @endif
        </h3>

        <div class="mt-3 max-w-xl text-sm text-gray-600">
            <p>
                Quando a autenticação de dois fatores está habilitada, vocẽ tem mais segurança, uma chave aleatória é gerada durante a autenticação. Você pode recuperar esta chave com o aplicatiivo Google Authenticator no seu smartphone.                
            </p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        A autenticação de dois fatores está autenticada. Escanei o QR Code usando o aplicativo Google Authenticator no seu smartphone                        
                    </p>
                </div>

                <div class="mt-4">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>
            @endif

            @if ($showingRecoveryCodes)
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        Armaze os códigos de recuperação em um local seguro. Eles podem ser úteis caso algum problema ocorra com o aplicativo do seu smartphone.                        
                    </p>
                </div>

                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-5">
            @if (! $this->enabled)
                <x-jet-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-jet-button type="button" wire:loading.attr="disabled">
                        Habilitar
                    </x-jet-button>
                </x-jet-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-jet-confirms-password wire:then="regenerateRecoveryCodes">
                        <x-jet-secondary-button class="mr-3">
                            Gerar novamente os códigos de recuperação
                        </x-jet-secondary-button>
                    </x-jet-confirms-password>
                @else
                    <x-jet-confirms-password wire:then="showRecoveryCodes">
                        <x-jet-secondary-button class="mr-3">
                            Mostrar os códigos de recuperação
                        </x-jet-secondary-button>
                    </x-jet-confirms-password>
                @endif

                <x-jet-confirms-password wire:then="disableTwoFactorAuthentication">
                    <x-jet-danger-button wire:loading.attr="disabled">
                        Desabilitar
                    </x-jet-danger-button>
                </x-jet-confirms-password>
            @endif
        </div>
    </x-slot>
</x-jet-action-section>
