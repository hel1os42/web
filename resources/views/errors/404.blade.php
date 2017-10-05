@extends('layouts.master')

@section('title', trans('errors.404'))

@section('content')
    <div class="title">{{$exception->getMessage() ?: trans('errors.404')}}</div>
@stop