@extends('layouts.master')

@section('title', 'Profile')

@section('content')


    <h1>Find best offers in best places:</h1>

    <div class="place">
        <form action="{{route('places.index')}}" target="_top">
            {{ csrf_field() }}
            <label for="category">Choose category:</label>
            <select id="place-category" name="category_ids[]">
            </select><br>
            <label for="latitude">Set latitude:</label>
            <input type="text" name="latitude" placeholder="40.7142540" value=""><br>
            <label for="latitude">Set longitude</label>
            <input type="text" name="longitude" placeholder="-74.0054797" value=""><br>
            <label for="latitude">Set radius (in meters):</label>
            <input type="text" name="radius" placeholder="1000" value=""><br>
            <input type="submit">
        </form>
    </div>
    <script type="text/javascript">
        function loadCategory(){
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === XMLHttpRequest.DONE) {
                    if (xmlhttp.status === 200) {
                        let sel = document.getElementById("place-category");
                        sel.innerHTML = xmlhttp.responseText;
                    }
                    else if (xmlhttp.status === 400) {
                        alert('There was an error 400');
                    }
                    else {
                        alert('something else other than 200 was returned');
                    }
                }
            };

            xmlhttp.open("GET", "{{route('categories')}}", true);
            xmlhttp.send();
        }

        loadCategory();

    </script>

    <div class="profile">

        <h3>Your profile info:</h3>
        <img src="{{route('profile.picture.show')}}"><br>
        Name: {{$name}}<br>
        ID: {{$id}}<br>
        Your email: {{$email}}<br>
        Your invite link: <a
                href="{{route('registerForm', $invite_code)}}">{{route('registerForm', $invite_code)}}</a><br>
        <br>Links profile:<br>
        <a href="{{route('referrals')}}">{{route('referrals')}}</a><br>
        <a href="{{route('profile.place.show')}}">{{route('profile.place.show')}}</a><br>
        <a href="{{route('profile.place.offers')}}">{{route('profile.place.offers')}}</a><br>
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
