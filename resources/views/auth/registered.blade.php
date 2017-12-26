@extends('layouts.ajax')
@section('content')
    Congratulations
    @if(isset($name))
        {{$name}}
    @endif
    , you have successfully registered.
@stop