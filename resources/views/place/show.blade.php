@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')

@if(!auth()->user()->isImpersonated() && auth()->user()->isAdvertiser())
    <a href="{{route('places.edit', [$id])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit place</a>
@endif
<div class="container">
    <div class="row">
        <div class="col-md-3"><strong>Name:</strong></div>
        <div class="col-md-9">{{ $name }}</div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Description:</strong></div>
        <div class="col-md-9">{{ $description ?: '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>About:</strong></div>
        <div class="col-md-9">{{ $about ?: '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Address:</strong></div>
        <div class="col-md-9">{{ $address ?: '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Place category:</strong></div>
        <div class="col-md-9"></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Retail Type:</strong></div>
        <div class="col-md-9"></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Specialties:</strong></div>
        <div class="col-md-9"></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Tags:</strong></div>
        <div class="col-md-9"></div>
    </div>
    <div class="row">
        <div class="col-md-3"><strong>Position:</strong></div>
        <div class="col-md-9">{{ $latitude }},{{ $longitude }}, radius: {{ $radius }}</div>
    </div>
    <p><strong>Place picture:</strong></p>
    <div><img src="{{route('places.picture.show', [$id, 'cover'])}}" alt="Place picture"></div>
    <p><strong>Place cover:</strong></p>
    <div><img src="{{route('places.picture.show', [$id, 'picture'])}}" alt="Place cover"></div>
</div>

@stop