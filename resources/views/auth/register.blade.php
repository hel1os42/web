@extends('layouts.ajax')
@section('content')
    <form action="{{route('register')}}" method="post" target="_top">
        {{ csrf_field() }}
        <input type="name" name="name" placeholder="name" value="{{old('name')}}"> <br>
        <input type="email" name="email" placeholder="email" value="{{old('email')}}><br>
    <input type=" password" name="password" placeholder="password"><br>
        <input type="password" name="password_confirm" placeholder="password_confirmation"><br>
        <input type="hidden" name="referrer_id" value="{{$referrer_id}}"/><br>
        <input type="submit">
    </form>
@stop
