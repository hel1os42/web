@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')

<div class="container">
    <h1>Place information</h1>
    <div class="text-right">
        <a href="{{ route('places.edit', [$id]) }}" class="btn btn-nau"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit place</a>
    </div>
    <div class="row">
        <div class="col-xs-3"><p><strong>Name:</strong></p></div>
        <div class="col-xs-9"><p>{{ $name }}</p></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><p><strong>Description:</strong></p></div>
        <div class="col-xs-9"><p>{{ $description ?: '-' }}</p></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><p><strong>About:</strong></p></div>
        <div class="col-xs-9"><p>{{ $about ?: '-' }}</p></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><p><strong>Address:</strong></p></div>
        <div class="col-xs-9"><p>{{ $address ?: '-' }}</p></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><p><strong>Alias:</strong></p></div>
        <div class="col-xs-9"><p>{{ $alias ?: '-' }}</p></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><p><strong>Place category:</strong></div>
        <div class="col-xs-9"><p id="placeInfoCategory"></p></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><p><strong>Retail Type:</strong></div>
        <div class="col-xs-9"><p id="placeInfoRetailTypes"></p></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><p><strong>Specialties:</strong></div>
        <div class="col-xs-9"><p id="placeInfoSpecialities"></p></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><p><strong>Tags:</strong></div>
        <div class="col-xs-9"><p id="placeInfoTags"></p></div>
    </div>
    <div class="row">
        <div class="col-xs-3"><p><strong>Position:</strong></div>
        <div class="col-xs-9"><p>{{ $latitude }},{{ $longitude }}, radius: {{ $radius }}</div>
    </div>
    <div class="row">
        <div class="col-sm-3"><p><strong>Place logo:</strong></p></div>
        <div class="col-sm-9"><p><img src="{{ route('places.picture.show', [$id, 'picture']) }}" alt="Place logo" style="max-width: 100%;"></p></div>
    </div>
    <div class="row">
        <div class="col-sm-3"><p><strong>Place cover:</strong></p></div>
        <div class="col-sm-9"><p><img src="{{ route('places.picture.show', [$id, 'cover']) }}" alt="Place cover" style="max-width: 100%;"></p></div>
    </div>
</div>

@stop

@push('scripts')
<script>

    let rqURL = '/places/{{ $id }}?with=category;retailTypes;specialities;tags';
    srvRequest(rqURL, 'GET', 'json', function(response){
        console.log('Place categories, types, specialities, tags:');
        console.dir(response);
        document.getElementById('placeInfoCategory').innerText = response.category.length ? response.category[0].name : '-';
        document.getElementById('placeInfoRetailTypes').innerText = response.retail_types.length ? response.retail_types.map(function(e){ return e.name; }).join(', ') : '-';
        document.getElementById('placeInfoSpecialities').innerText = response.specialities.length ? response.specialities.map(function(e){ return e.name; }).join(', ') : '-';
        document.getElementById('placeInfoTags').innerText = response.tags.length ? response.tags.map(function(e){ return e.name; }).join(', ') : '-';
    });

</script>
@endpush
