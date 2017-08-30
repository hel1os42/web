@extends('layouts.master')

@section('title', 'List offer')

@section('content')
    @if ($transactions->count() > 0)
        @foreach ($transactions->get() as $transaction)
        <h2>
            <li>
                id: {{ $transaction->id }}
                sender: {{ $transaction->source_account_id }}
                destination: {{ $transaction->destination_account_id }}
                amount: {{ $transaction->amount }}
            </li>
        </h2>
        @endforeach
    @endif

@stop