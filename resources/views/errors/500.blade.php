@extends('layouts.master')

@section('title', trans('errors.500'))

@section('content')
    <div class="title">500</div>

    <h1>{{$exception->getMessage() ?: trans('errors.500')}}</h1>
@stop