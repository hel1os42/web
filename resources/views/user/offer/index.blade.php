@extends('layouts.master')

@section('title', 'Search offer')

@section('content')
    <div class="title">Search offer</div>
    <div class="offer">
        <form action="{{route('offers.index')}}" target="_top">
            {{ csrf_field() }}
            <select id="offer-category" name="category">
            </select><br>
            <input name="latitude" placeholder="latitude" value="{{old('latitude')}}"><br>
            <input name="longitude" placeholder="longitude" value="{{old('longitude')}}"><br>
            <input name="radius" placeholder="radius" value="{{old('radius')}}"><br>
            <input type="submit">
        </form>
        @if(isset($data))
            <h2>Results</h2>
            @foreach($data as $offer)
                <a href="{{route('offer.show', $offer->id)}}">{{$offer->name}}</a><br>
            @endforeach
        @endif
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
        xmlhttp.send();
    </script>
@stop
