@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')
    <div class="col-md-10 col-md-offset-1">
        <div class="card">
            <div class="content">
                <div class="img-container text-center">
                    <img src="{{route('places.picture.show', [$id, 'cover'])}}"><br>
                </div>
                <div class="img-container text-center">
                    <img src="{{route('places.picture.show', [$id, 'picture'])}}"><br>
                </div>
                <h1>{{$name}}</h1>
                <h4>{{$description}}</h4>
                <h4>{{$address}}</h4>
                <p>{{$about}}</p>
                <a href="{{route('profile.place.offers')}}"></a>
            </div>
        </div>
    </div>
@stop