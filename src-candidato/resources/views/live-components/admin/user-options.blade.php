<div>
    <div class="border-2 border-yellow-300 rounded text-yellow-700 mt-2 p-2 text-center">
        E-mail verificado: {{ $userState['email_verified_at'] ? 'Sim' : 'Não'}}
        @if(!$userState['email_verified_at'])
        <button
            class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded"
            type="button" wire:click="validateEmail()">
            Validar E-mail
        </button>
        @endif
    </div>
    <div class="border-2 border-yellow-300 rounded text-yellow-700 mt-2 p-2 text-center">
        Autenticação de 2 fatores: {{ $userState['two_factor_secret'] ? 'Sim' : 'Não'}}
        @if($userState['two_factor_secret'])
        <button
            class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded"
            type="button" wire:click="removeTwoFactorAuthentication()">
            Remover
        </button>
        @endif
    </div>
    <div class="border-2 border-yellow-300 rounded text-yellow-700 mt-2 p-2 text-center">        
        Gerar senha aleatória:        
        <button
            class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded"
            type="button" wire:click="changePassword()">
            Gerar
        </button>
        @if($newPassword)
            <p>   A senha gerada é {{ $newPassword }}</p>
        @endif
    </div>    
</div>