@extends('layouts.master')

@section('title', 'Created transactions')

@section('content')
    <div>
        <h2>
            <pre>
                sender: {{$source_account_id}}
                destination: {{$destination_account_id}}
                amount: {{$amount}}
                created: {{$created_at}}
                updated: {{$updated_at}}
            </pre>
        </h2>
    </div>
    @endif

@stop