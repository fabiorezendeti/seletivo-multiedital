@component('mail::message')

{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
# @lang('Hello!')

@endif

Pedimos que avalie nosso atendimento com relação à {{$content}}.

<img src="{{ url('img/rating_service02.png') }}" width="100%" alt="{{ config('app.name') }}">

Sua avaliação é muito importante pois nos ajudará a continuar aprimorando nossos serviços.

Para acessar, clique no botão abaixo:

@component('mail::button', ['url' => $link, 'color' => 'green'])
    {{ $actionText }}
@endcomponent

Ou copie e cole o seguinte link no seu navegador:

<span class="break-all"><a href="{{$link}}" target="_blank">{{$link}}</a></span>



@endcomponent
