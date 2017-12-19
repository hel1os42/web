@extends('layouts.master')

@section('title', 'List offer')

@section('content')
    @if (isset($data))
        <h2>
            @foreach ($data as $transaction)
                <li>
                    @foreach($transaction as $field)
                    {{ $field }}
                    @endforeach
                </li>
            @endforeach
        </h2>
    @endif
@stop