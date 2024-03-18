@extends('layouts.manager.lottery')

@section('notice',"Edital {$notice->number}")@show

@section('top-menu-items')
<li
    class="block md:inline md:float-left md:pb-3 px-4 border-b-2 border-green-500 hover:border-green-700 text-green-100 hover:text-white cursor-pointer text-base tracking-wide">
    <a href="{{ route('admin.notices.show',['notice'=>$notice]) }}" class="uppercase">
        Edital {{$notice->number}}
    </a>
</li>
@endsection


@section('content-app')
<h2 class="w-full text-center text-5xl bg-white text-portal">
    {{$notice->description}}
</h2>
<x-manager.vertical-align-cards>
    <form method="POST" id="delete-form">
        @csrf
        @method('delete')
    </form>
    @foreach ($offers as $offer)
    <x-manager.vertical-align-card-item 
        :text="$offer->courseCampusOffer->course->name"
        :title="$offer->courseCampusOffer->campus->name">
        @cannot('hasLotteryDraw',$offer)
        <a href="{{ route('admin.notices.lottery-draw.make',['notice'=>$notice,'offer'=>$offer]) }}"
            class="bg-red-500 mt-3 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
            Sortear
        </a>
        @else
        <a href="{{ route('admin.notices.lottery-draw.make',['notice'=>$notice,'offer'=>$offer]) }}"
            class="bg-green-500 mt-3 hover:bg-green-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
            Resultado
        </a>        
        <a href="{{ route('admin.notices.lottery-draw.classification-report',['notice'=>$notice,'offer'=>$offer]) }}"
            class="bg-blue-500 mt-3 hover:bg-blue-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full"
            target="_blank">
            Relatório de Classificação
        </a>
        @can('doesntHaveLotteryCalls',$offer)
        <button type="submit" formaction="{{ route('admin.notices.lottery-draw.destroy',['notice'=>$notice,'offer'=>$offer]) }}"
            form="delete-form"
            class="bg-red-500 mt-3 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
            Desfazer
        </button>
        @endcan
        @endcannot        
    </x-manager.vertical-align-card-item>
    @endforeach
</x-manager.vertical-align-cards>
@endsection