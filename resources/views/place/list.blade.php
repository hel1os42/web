@extends('layouts.master')

@section('title', 'NAU show Place list')

@section('content')
    <h1>List</h1>
    <ul style="font-size: small; color:black; font-size: 21px;">
        @foreach ($data as $place)

            <li>{{$place['name']}}<br>
                {{$place['description']}}<br>
                {{$place['about']}}<br>
                {{$place['address']}}<br>
                {{$place['latitude']}}<br>
                {{$place['longitude']}}<br>
                {{$place['radius']}}<br>
            </li>
        @endforeach
    </ul>
@stop