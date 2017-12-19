@extends('layouts.error')

@section('title', trans('errors.405'))

@section('error-num')
    405!
@stop
@section('error-msg')
    <p class="heading_b">{{$exception->getMessage() ?: trans('errors.405')}}</p>
@stop
