@extends('layouts.auth')
@section('content')
    <form action="{{route('register')}}" method="post" target="_top">
        {{ csrf_field() }}
        <input type="text" name="name" placeholder="name" value="{{old('name')}}"> <br>
        <input type="email" name="email" placeholder="email" value="{{old('email')}}"><br>
        <input type="password" name="password" placeholder="password"><br>
        <input type="password" name="password_confirm" placeholder="password_confirmation"><br>
        <input type="hidden" name="referrer_id" value="{{$referrer_id}}"/><br>
        <input type="submit">
    </form>
@stop
@extends('layouts.auth')
@section('content')
{!! Form::open(array('route' => 'register', 'method' => 'POST', 'class' => 'huyeta')) !!}
    <div class="card card-login card-hidden">
        <div class="header text-center">
            <h3 class="title">Register</h3>
        </div>
        <div class="content">
            <div class="social-line text-center">
                @include(
                    "form.input",
                    [
                        "type" => "text", 
                        "params" => ["placeholder" => "name", "class" => "form-control input-no-border"],
                        "name" => "name",
                    ],
                    ["label" => "Name"]
                )
                @include(
                    "form.input",
                    [
                        "type" => "email", 
                        "params" => ["placeholder" => "email", "class" => "form-control input-no-border"],
                        "name" => "email",
                    ],
                    ["label" => "Email"]
                )
                @include(
                    "form.input",
                    [
                        "type" => "password",
                        "params" => [
                            "placeholder" => "password",
                            "class" => "form-control input-no-border"
                        ],
                        "name" => "password",
                    ],
                    ["label" => "Password"]
                )
                @include(
                    "form.input",
                    [
                        "type" => "password",
                        "params" => [
                            "placeholder" => "confirm password",
                            "class" => "form-control input-no-border"
                        ], 
                        "name" => "password_confirm",
                    ],
                    ["label" => "Confirm password"]
                )
                <input class="btn btn-rose btn-wd btn-lg" type="submit">
            </div>
        </div>
    </div>
{!! Form::close() !!}

@stop
