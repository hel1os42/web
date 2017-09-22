@extends('layouts.master')

@section('title', trans('errors.400'))

@section('content')
    <div class="title">{{$exception->getMessage() ?: trans('errors.400')}}</div>
@stop