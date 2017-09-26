@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')
    <h1>Create your Place</h1>
    <div class="offer">
        <form action="{{route('places.store')}}" method="post" target="_top">
            {{ csrf_field() }}
            <input name="name" placeholder="name" value="{{old('name')}}"> <br>
            <input name="description" placeholder="description"
                   value="{{old('description')}}"><br>
            <textarea name="about" placeholder="about">{{old('description')}}</textarea><br>
            <input name="address" placeholder="address" value="{{old('address')}}"><br>
            <select id="place-category" name="categories_ids[]">
            </select><br>
            <input name="latitude" placeholder="latitude" value="{{old('latitude')}}"><br>
            <input name="longitude" placeholder="longitude" value="{{old('longitude')}}"><br>
            <input name="radius" placeholder="radius" value="{{old('radius')}}"><br>
            <input type="submit">
        </form>
    </div>


    <script type="text/javascript">
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                if (xmlhttp.status == 200) {
                    document.getElementById("place-category").innerHTML = xmlhttp.responseText;
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
        xmlhttp.send();
    </script>
@stop