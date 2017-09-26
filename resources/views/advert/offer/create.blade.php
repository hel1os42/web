@extends('layouts.master')

@section('title', 'Create offer')

@section('content')

    <div class="offer">
        <form action="{{route('advert.offers.store')}}" method="post" target="_top">
            {{ csrf_field() }}
            <input name="label" placeholder="label" value="{{old('label')}}"> <br>
            <input name="description" placeholder="description"
                   value="{{old('description')}}"><br>
            <input name="reward" placeholder="reward" value="{{old('reward')}}"><br>
            <input name="start_date" placeholder="start_date" value="{{old('start_date')}}"><br>

            <input name="finish_date" placeholder="finish_date" value="{{old('finish_date')}}"><br>
            <input name="start_time" placeholder="start_time" value="{{old('start_time')}}"><br>
            <input name="finish_time" placeholder="finish_time" value="{{old('finish_time')}}"><br>
            <select id="offer-category" name="category_id">
            </select><br>
            <input name="max_count" placeholder="max_count" value="{{old('max_count')}}"><br>
            <input name="max_for_user" placeholder="max_for_user" value="{{old('max_for_user')}}"><br>
            <input name="max_per_day" placeholder="max_per_day" value="{{old('max_per_day')}}"><br>
            <input name="max_for_user_per_day" placeholder="max_for_user_per_day"
                   value="{{old('max_for_user_per_day')}}"><br>
            <input name="user_level_min" placeholder="user_level_min" value="{{old('user_level_min')}}"><br>
            <input name="latitude" placeholder="latitude" value="{{old('latitude')}}"><br>
            <input name="longitude" placeholder="longitude" value="{{old('longitude')}}"><br>
            <input name="radius" placeholder="radius" value="{{old('radius')}}"><br>
            <input name="country" placeholder="country" value="{{old('country')}}"><br>
            <input name="city" placeholder="city" value="{{old('city')}}"><br>
            <input type="submit">
        </form>
    </div>


    <script type="text/javascript">
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                if (xmlhttp.status == 200) {
                    document.getElementById("offer-category").innerHTML = xmlhttp.responseText;
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