@extends('layouts.ajax')
AJAX
@section('content')
    <form action="{{route('login')}}" method="post" target="_top">
        {{ csrf_field() }}

        <input type="email" name="email" placeholder="email" value="{{old('email')}}">
        <input type="password" name="password" placeholder="password">
        <input type="submit">
    </form>
    <br>OR<br>

    <form action="{{route('login')}}" method="post" target="_top">
        {{ csrf_field() }}

        <input type="text" name="phone" placeholder="phone" value="{{old('email')}}">
        <input type="text" name="code" placeholder="code">
        <input type="submit">
    </form>
@stop
