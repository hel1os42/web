@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')
    <img src="{{route('place.picture.show', [$id, 'picture'])}}"><br>
    <img src="{{route('place.picture.show', [$id, 'cover'])}}"><br>
    <h1>{{$name}}</h1>
    <h4>{{$description}}</h4>
    <h4>{{$address}}</h4>
    <p>{{$about}}</p>
    <a href="{{route('profile.place.offers')}}"></a>

    <br>Set logo:<br>
    <form method="POST" action="{{route('place.picture.store')}}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="picture">
        <button type="submit">Upload</button>
    </form>
    <br>Set cover:<br>
    <form method="POST" action="{{route('place.cover.store')}}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="picture">
        <button type="submit">Upload</button>
    </form>
@stop