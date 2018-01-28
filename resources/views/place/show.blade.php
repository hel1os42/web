@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')
    @if(!auth()->user()->isImpersonated() && auth()->user()->isAdvertiser())
        <a href="{{route('places.edit', [$id])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit place</a>
    @endif
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
            </div>
        </div>
    </div>
@stop