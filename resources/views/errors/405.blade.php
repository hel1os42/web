@extends('layouts.master')

@section('title', trans('errors.405'))

@section('content')
    <div class="title">{{$exception->getMessage() ?: trans('errors.405')}}</div>
@stop