@props(['disabled' => false,'value' => null])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-input rounded-md shadow-sm']) !!}>{{ $value }}</textarea>    
