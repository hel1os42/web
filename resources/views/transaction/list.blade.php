@extends('layouts.master')

@section('title', 'List offer')

@section('content')
    @if (isset ($transactions))
        @foreach ($transactions as $transaction)
        <li>{{ $transaction }}</li>
        @endforeach
    @endif

@stop