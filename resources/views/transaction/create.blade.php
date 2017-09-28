@extends('layouts.master')

@section('title', 'Create offer')

@section('content')

    <form action="{{ route('transaction.complete') }}" method="post" target="_top">
        {{ csrf_field() }}

        <label>source</label><input type="text" name="source" value="{{ $source }}"> <br>
        <label>destination</label><input type="text" name="destination" value="{{ old('destination') }}"> <br>
        <label>amount</label><input type="text" name="amount" value="{{ old('amount') }}"> <br>
        <input type="submit" value="Send">
    </form>

@stop