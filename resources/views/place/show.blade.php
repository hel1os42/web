@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')
    <img src="{{route('place.picture.show', [$id, 'picture'])}}"><br>
    <img src="{{route('place.picture.show', [$id, 'cover'])}}"><br>
    <h1>{{$name}}</h1>
    <h4>{{$description}}</h4>
    <h4>{{$address}}</h4>
    <p>{{$about}}</p>
@stop