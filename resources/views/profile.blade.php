@extends('layouts.master')

@section('title', 'Profile')

@section('content')
    <div class="profile">
        <img src="{{route('profile.picture.show')}}"><br>
        {{$id}}<br>
        Your email: {{$email}}<br>
        Your invite link: <a
                href="{{route('registerForm', $invite_code)}}">{{route('registerForm', $invite_code)}}</a><br>
        <br>Links profile:<br>
        <a href="{{route('referrals')}}">{{route('referrals')}}</a><br>
        <a href="{{route('referrals')}}">{{route('referrals')}}</a><br>
        <br>Offers:<br>
        <a href="{{route('advert.offers.create')}}">{{route('advert.offers.create')}}</a><br>
        <a href="{{route('advert.offers.index')}}">{{route('advert.offers.index')}}</a><br>
        <a href="{{route('offers.index')}}">{{route('offers.index')}}</a><br>
        <br>Operations:<br>
        <a href="{{ route('transactionList') }}">{{ route('transactionList')}}</a><br>
        <a href="{{ route('transactionCreate') }}">{{ route('transactionCreate') }}</a><br>
        <br>Places:<br>
        <a href="{{ route('places.create') }}">{{ route('places.create') }}</a><br>
        <br>Photo:<br>
        <form method=" POST" action="{{route('profile.picture.store')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="file" name="picture">
            <button type="submit">Set photo</button>
        </form>


    </div>
@stop
