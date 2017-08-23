@extends('layouts.master')

@section('title', 'Profile')

@section('content')
    <div class="profile">
        <h3>Slaves(: </h3>
        @foreach($data as $user)
            {{$user['name']}} | {{$user['id']}}<br>
            /---------------------------------<br>
        @endforeach
    </div>
@stop
