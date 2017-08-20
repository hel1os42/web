@extends('layouts.master')

@section('title', 'List offers')

@section('content')
    <div class="offer">
        @foreach ($data as $offer)
            @foreach ($offer as $val)
                <p>{{$val}}</p>
            @endforeach
            //-------------------------------------------
        @endforeach
    </div>
@stop
