@extends('layouts.master')

@section('title', 'Info about transaction')

@section('content')

    @if (isset ($transaction))
        <h2>
            sender acc id - {{ $transaction['source_account_id']  }} <br>
            destination acc id - {{ $transaction['destination_account_id']  }} <br>
            amount - {{ $transaction['amount']  }} <br>
        </h2>
    @endif

@stop