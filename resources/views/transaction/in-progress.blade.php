@extends('layouts.master')

@section('title', 'Transaction in progress....')

@section('content')
    <div>
        <h2>
                Your transaction in progress..... Result of the transaction you will see on <a href="{{route('transaction.list')}}">this page</a>.
        </h2>
    </div>

@stop