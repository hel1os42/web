@extends('layouts.master')

@section('title', 'Created transactions')

@section('content')
    <div>
        <h2>
                sender: {{$source_account_id}}<br>
                destination: {{$destination_account_id}}<br>
                amount: {{$amount}}
        </h2>
    </div>

@stop