@extends('errors::illustrated-layout')

@section('title', __('Not Found'))
@section('code', '405')
@section('message', 'Método não permitido')
@section('details', 'O método utilizado para acessar este recurso não é permitido')
