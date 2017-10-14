@extends('layouts.error')

@section('title', trans('errors.500'))

@section('error-num')
    500!
@stop
@section('error-msg')
    <p class="heading_b">{{$exception->getMessage() ?: trans('errors.500')}}</p>
@stop
