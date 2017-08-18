@extends('layouts.master')

@section('title', 'Profile')

@section('content')
    <div class="profile">
        Your email: {{$email}}<br>
        Your invite link: <a
                href="{{route('registerForm', $invite_code)}}">{{route('registerForm', $invite_code)}}</a><br>
        <br>Links:<br>
        <a href="{{route('advert.offers.create')}}">{{route('advert.offers.create')}}</a><br>
        <a href="{{route('advert.offers.index')}}">{{route('advert.offers.index')}}</a><br>
        <a href="{{route('offers.index')}}">{{route('offers.index')}}</a><br>
    </div>
@stop
