@extends('layouts.manager.lottery')

@section('notice',"Edital {$notice->number}")@show

@section('top-menu-items')
<li class="block md:inline md:float-left md:pb-3 px-4 border-b-2 border-green-500
     hover:border-green-700 text-green-100 hover:text-white cursor-pointer text-base tracking-wide">
    <a href="{{ route('admin.notices.lottery-draw.index',['notice'=>$notice]) }}"" class="uppercase">
        Lista de Ofertas
    </a>
</li>
@endsection

@section('content-app')
<div x-data="subscriptions()">
    <h2 class=" w-full text-center text-2xl bg-white text-portal">
        {{$offer->getString()}}
    </h2>
    <div class="w-full bg-green-500 p-5 text-white text-2xl">
        <label for="seed">Semente:
        </label>
        <input type="number" x-model="seed" class="text-green-500  p-5" />
        Total de inscritos: <span x-text="totalSubscriptions"> </span>
        @cannot('hasLotteryDraw',$offer)
        <button type="button" x-show="!lotteryResult" class="border-2 border-white hover:bg-green-700  rounded p-5" x-on:click="lotteryDraw()">
            SORTEAR </button>
        @endcannot        
        <button type="button" x-show="finalList.length > 0" class="border-2 border-white hover:bg-green-700  rounded p-5" x-on:click="lotteryDraw(true)">
            Auditar </button>        
    </div>
    <div class="w-full bg-white text-green-700 p-5" x-show="lotteryResult">
        <h2 class="text-xl">Os números sorteados são:</h2>
        <p x-text="lotteryResult"></p>
        <p x-show="serverStringCheck" class="bg-green-500 p-2">Ordem do banco de dados: Conferido e está igual</p>
    </div>
    <div class="flex">
        <div class="p-5 flex-1" x-show="auditedList.length < 1">
            <h2 class="text-2xl text-white p-5">Elegíveis para Sorteio</h2>
            <table class="bg-white rounded w-full">
                <thead>
                    <tr class="border-b-2">
                        <th>Número de Sorteio</th>
                        <th>Número da Inscrição</th>
                        <th>Nome do Candidato</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in list" :key="index">
                        <tr class="border-b-2">
                            <td class="p-2 text-center" x-text="item.lottery_number"></td>
                            <td class="p-2 text-center" x-text="item.subscription_number"></td>
                            <td class="p-2" x-text="item.user_name"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="p-5 flex-1/2" x-show="auditedList.length > 0">
            <h2 class="text-2xl text-white p-5">Auditoria</h2>
            <table class="bg-white rounded w-full">
                <thead>
                    <tr class="border-b-2">
                        <th>Posição</th>
                        <th>Número de Sorteio</th>                        
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in auditedList" :key="index">
                        <tr class="border-b-2 text-right">
                            <td class="p-2" x-text="index + 1"></td>
                            <td class="p-2" x-text="item.lottery_number"></td>                            
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>   

        <div class="p-5 flex-1">
            <h2 class="text-2xl text-white p-5">Resultado do Sorteio</h2>
            <table class="bg-white rounded w-full">
                <thead>
                    <tr class="border-b-2">
                        <th>Posição</th>
                        <th>Número de Sorteio</th>
                        <th>Número da Inscrição</th>
                        <th>Nome do Candidato</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in finalList" :key="index">
                        <tr class="border-b-2">
                            <td class="p-2 text-center" x-text="item.global_position"></td>
                            <td class="p-2 text-center" x-text="item.lottery_number"></td>
                            <td class="p-2 text-center" x-text="item.subscription_number"></td>
                            <td class="p-2" x-text="item.user_name"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>


    </div>
    <div class="w-full bg-white rounded p-5">
        <h3 class="text-2xl">Informações técnicas</h3>
        <dl x-data="systemInfo()">
            <dt class="font-bold">Plataforma:</dt>
            <dd x-text="platform"></dd>
            <dt class="font-bold">Aplicação:</dt>
            <dd x-text="appName"></dd>
            <dt class="font-bold">Versão:</dt>
            <dd x-text="appVersion"></dd>
            <dt class="font-bold">Agente:</dt>
            <dd x-text="userAgent"></dd>
            <dt class="font-bold">Versão do Seed Random:</dt>
            <dd x-text="seedramdonVersion"></dd>
        </dl>
    </div>
</div>
@endsection

@push("js")
<script>
    function systemInfo()
    {
        return {
            platform: navigator.platform,
            appName: navigator.appName,
            appVersion: navigator.appVersion,
            userAgent: navigator.userAgent,
            seedramdonVersion: '3.0.5'
        }
    }
    
    function subscriptions() 
    {
        return {
            list: @json($subscriptions),                
            finalList: @json($finalList),
            auditedList: [],
            serverStringCheck: false,
            lotteryResult: null,
            totalSubscriptions: {{ $subscriptions->count() }},                        
            seed: {{ $offer->lottery_seed ?? time() }},                        
            lotteryDraw: function(audit = false) {
                seedrandom(this.seed, {global: true});
                let subscriptionsCount = this.totalSubscriptions;                    
                
                let consumed = new Array(this.list);
                let sortList = new Array(this.list);
                
                for(var i = 0; i < subscriptionsCount; i++) {
                    consumed[i] = 1+i;
                    sortList[i] = 0;
                }

                for(var i = 0; i < subscriptionsCount; i++) {
                    var randomic = Math.floor(Math.random() * subscriptionsCount);
                    while(consumed[randomic] == 0) {
                        randomic = (1+randomic) % subscriptionsCount;
                    }
                    sortList[i] = consumed[randomic];
                    consumed[randomic] = 0;
                }
                
                this.lotteryResult = sortList.join(', ');

                if (!audit) {
                    for (var i = 0 ; i < sortList.length; i++) {
                        this.finalList[i] = this.list[sortList[i]-1]
                    }
                    this.save();
                } else {
                    for (var i = 0 ; i < sortList.length; i++) {
                        this.auditedList[i] = this.list[sortList[i]-1]
                    }
                }
                return sortList;
            },
            save: function() {
                axios.post("#",{
                        finalList: this.finalList,
                        lotteryResult: this.lotteryResult,
                        seed: this.seed,
                        systemInfo: systemInfo()
                    }
                    ).then((response)=>{                                
                        if (this.lotteryResult === response.data.listString) {
                            this.serverStringCheck = true
                        }
                        this.finalList = response.data.savedList
                        console.log(this.finalList)                        
                    }).catch((error)=>{
                        window.alert('Um erro ocorreu',error.message)
                        this.finalList = []
                        console.log(error.message)
                    })
            },
        }
    }        
        
</script>

@endpush