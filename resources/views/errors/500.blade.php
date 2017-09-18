@extends('layouts.master')

@section('title', trans('errors.404'))

@section('content')
    <div class="title">500</div>

    <h1>{{$exception->getMessage()}}</h1>
@stop