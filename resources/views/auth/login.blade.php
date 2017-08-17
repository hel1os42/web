@extends('layouts.ajax')
AJAX
@section('content')
    <form action="{{route('login')}}" method="post" target="_top">
        {{ csrf_field() }}

        <input type="email" name="email" placeholder="email" value="{{old('email')}}">
        <input type="password" name="password" placeholder="password">
        <input type="submit">
    </form>
@stop
