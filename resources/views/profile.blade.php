@extends('layouts.master')

@section('title', 'Profile')

@section('content')
    <div class="profile">
        <img src="{{route('profile.photo.show')}}"><br>
        {{$id}}<br>
        Your email: {{$email}}<br>
        Your invite link: <a
                href="{{route('registerForm', $invite_code)}}">{{route('registerForm', $invite_code)}}</a><br>
        <br>Links:<br>
        <a href="{{route('referrals')}}">{{route('referrals')}}</a><br>
        <a href="{{route('advert.offers.create')}}">{{route('advert.offers.create')}}</a><br>
        <a href="{{route('advert.offers.index')}}">{{route('advert.offers.index')}}</a><br>
        <a href="{{route('offers.index')}}">{{route('offers.index')}}</a><br>
        <br>Operations:<br>
        <a href="{{ route('transactionList') }}">route(transactionList)</a><br>
        <a href="{{ route('transactionCreate') }}">route(transactionCreate)</a><br>
        <br>Photo:<br>
        <form method="POST" action="{{route('profile.photo.store')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="file" name="photo">
            <button type="submit">Set photo</button>
        </form>


    </div>
@stop
