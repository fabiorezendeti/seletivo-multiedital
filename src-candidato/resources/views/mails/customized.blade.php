@component('mail::message')

{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
# @lang('Hello!')

@endif

{!! nl2br(e($content)) !!}


@component('mail::button', ['url' => url('/'), 'color' => 'primary'])
    {{ $actionText }}
@endcomponent


{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>
{{ config('app.name') }}
@endif


@slot('subcopy')

@lang(
"If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below into your web browser:",
[
'actionText' => 'Clique aqui para acompanhar sua inscrição',
]
) <span class="break-all"><a href="{{ url('/') }}"> {{ url('/') }} </a></span>
<br>
<span style="font-size: 8px">{{ $number }}/{{$total}}</span>
@endslot


@endcomponent
