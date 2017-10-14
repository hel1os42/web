@extends('layouts.error')

@section('title', trans('errors.400'))

@section('error-num')
    400!
@stop
@section('error-msg')
    <p class="heading_b">{{$exception->getMessage() ?: trans('errors.400')}}</p>
@stop
