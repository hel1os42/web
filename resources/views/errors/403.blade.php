@extends('layouts.error')

@section('title', trans('errors.403'))

@section('error-num')
    403!
@stop
@section('error-msg')
    <p class="heading_b">{{$exception->getMessage() ?: trans('errors.403')}}</p>
@stop
