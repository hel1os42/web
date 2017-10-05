@extends('layouts.master')

@section('title', trans('errors.403'))

@section('content')
    <div class="title">{{$exception->getMessage() ?: trans('errors.403')}}</div>
@stop