@extends('layouts.master')

@section('title', 'Link was sent')

@section('content')
    <div class="container">
        {{ $message }}
    </div>
@stop
