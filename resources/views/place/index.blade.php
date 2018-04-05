@extends('layouts.master')

@section('title', 'NAU show Place list')

@section('content')
    <h1>Places:</h1>
    <ul style="font-size: small; color:black; font-size: 21px;">
        @foreach ($data as $place)
            @if($place['offers_count'] > 0)
                <li><a href="{{route('places.show', ['uuid' => $place['id']])}}">{{$place['name']}}</a><br>
                    description: {{$place['description']}}<br>
                    Offers count: {{$place['offers_count']}}
                </li>
            @endif
        @endforeach
    </ul>

    @if(request('latitude'))
        <iframe width="800" height="500" src="https://maps.google.com/maps?q={{request('latitude')}},{{request('longitude')}}&hl=en&output=embed"></iframe>
    @endif
@stop
