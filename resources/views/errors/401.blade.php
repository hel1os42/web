@extends('layouts.error')

@section('title', trans('errors.401'))

@section('error-num')
    401!
@stop
@section('error-msg')
    <p class="heading_b">{{$exception->getMessage() ?: trans('errors.401')}}</p>
@stop
