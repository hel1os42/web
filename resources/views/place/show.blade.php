@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')
    <h1>{{$name}}</h1>
    <h4>{{$description}}</h4>
    <h4>{{$address}}</h4>
    <p>{{$about}}</p>
@stop