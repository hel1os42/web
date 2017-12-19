@extends('layouts.error')

@section('title', trans('errors.404'))

@section('error-num')
    404!
@stop
@section('error-msg')
    <p class="heading_b">{{$exception->getMessage() ?: trans('errors.404')}}</p>
@stop
