@extends('layouts.master')

@section('title', 'NAU show Users list')

@section('content')
    <h1>Users:</h1>
    <ul style="font-family: serif; color:black; font-size: 26px; text-align: left;">
        @foreach ($data as $user)
            <li>
                <strong>Email:</strong> {{$user['id']}}<br>
                <strong>Name:</strong> {{$user['name']}}<br>
                <strong>Email:</strong> {{$user['email']}}<br>
                <strong>Phone:</strong> {{$user['phone']}}<br>
                <strong>Roles:</strong>
                @foreach ($user['roles'] as $role)
                    {{$role['name']}},&nbsp
                @endforeach
                <br><br>
            </li>
        @endforeach
    </ul>
@stop