@extends('layouts.master')

@section('title', 'Show offer')

@section('content')
        <div class="offer">
            {{$label}}<br>
            {{$description}}
        </div>
@stop
