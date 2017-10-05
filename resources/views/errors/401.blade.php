@extends('layouts.master')

@section('title', trans('errors.401'))

@section('content')
    <div class="title">{{$exception->getMessage() ?: trans('errors.401')}}</div>
@stop