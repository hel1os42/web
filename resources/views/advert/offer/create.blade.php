@extends('layouts.master')

@section('title', 'Create offer')

@section('content')

    <section class="offer">
        <div class="offer-form">
            <form action="{{route('advert.offers.store')}}" method="post" target="_top">
                {{ csrf_field() }}
                <label for='label'>label</label>
                <input id="label" name="label" placeholder="label" value="{{old('label')}}">

                <label for='description'>description</label>
                <input id="description" name="description" placeholder="description" value="{{old('description')}}">

                <label for='reward'>reward</label>
                <input id="reward" name="reward" type="number" placeholder="reward" value="{{old('reward')}}">

                <label for='start_date'>start date</label>
                <input id="start_date" name="start_date" placeholder="start_date" value="{{old('start_date')}}">

                <label for='finish_date'>finish date</label>
                <input id="finish_date" name="finish_date" placeholder="finish_date" value="{{old('finish_date')}}">

                <label for='start_time'>start time</label>
                <input id="start_time" name="start_time" type="text" placeholder="start_time" value="{{old('start_time')}}">

                <label for='finish_time'>finish time</label>
                <input id="finish_time" name="finish_time" type="text" placeholder="finish_time" value="{{old('finish_time')}}">

                <label for='offer_category'>offer-category</label>
                <select id="offer_category" name="category_id"></select>

                <label for='max_count'>max count</label>
                <input id="max_count" name="max_count" type="number" max="1000" min="0" placeholder="max_count" value="{{old('max_count')}}">

                <label for='max_for_user'>max for user</label>
                <input id="max_for_user" name="max_for_user" type="number" max="1000" min="0" placeholder="max_for_user" value="{{old('max_for_user')}}">

                <label for='max_per_day'>max per day</label>
                <input id="max_per_day" name="max_per_day" type="number" max="1000" min="0" placeholder="max_per_day" value="{{old('max_per_day')}}">

                <label for='max_for_user_per_day'>max for user per day</label>
                <input id="max_for_user_per_day" name="max_for_user_per_day" type="number" max="1000" min="0" placeholder="max_for_user_per_day"  value="{{old('max_for_user_per_day')}}">

                <label for='user_level_min'>user level min</label>
                <input id="user_level_min" name="user_level_min" type="number" max="1000" min="0" placeholder="user_level_min" value="{{old('user_level_min')}}">


                <label for='latitude'>latitude</label>
                <input id="latitude" name="latitude" placeholder="latitude" value="{{old('latitude')}}">

                <label for='longitude'>longitude</label>
                <input id="longitude" name="longitude" placeholder="longitude" value="{{old('longitude')}}">

                <label for='radius'>radius</label>
                <input id="radius" name="radius" type="number" placeholder="radius" value="{{old('radius')}}">

                <label for='country'>country</label>
                <input id="country" name="country" placeholder="country" value="{{old('country')}}">

                <label for='city'>city</label>
                <input id="city" name="city" type="text" placeholder="city" value="{{old('city')}}">

                <label for='address'>address</label>
                <input id="address" name="address" type="text" placeholder="address">

                <input type="submit">
            </form>
        </div>
    </section>

    <section class="map">
            <div id="map"></div>
    </section>


    <script type="text/javascript">
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                if (xmlhttp.status == 200) {
                    document.getElementById("offer_category").innerHTML = xmlhttp.responseText;
                }
                else if (xmlhttp.status == 400) {
                    alert('There was an error 400');
                }
                else {
                    alert('something else other than 200 was returned');
                }
            }
        };

        xmlhttp.open("GET", "{{route('categories')}}", true);
        xmlhttp.setRequestHeader('Accept', 'application/json');
        xmlhttp.send();
    </script>

@stop