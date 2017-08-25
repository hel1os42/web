@extends('layouts.master')

@section('title', 'Create offer')

@section('content')

    <form action="{{ route('transactionComplete') }}" method="post" target="_top">
        {{ csrf_field() }}

        <label>sender</label><input type="text" name="sender" value="{{ $id }}"> <br>
        <label>destination</label><input type="text" name="destination" value="{{ old('destination') }}"> <br>
        <label>amount</label><input type="text" name="amount" value="{{ old('amount') }}"> <br>
        <input type="submit" value="Send">
    </form>

@stop