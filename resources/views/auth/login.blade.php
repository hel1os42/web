@extends('layouts.auth')
@section('content')
{!! Form::open(array('route' => 'login', 'method' => 'POST')) !!}
    <div class="card card-login card-hidden">
        <div class="content">
            <div class="social-line text-center">
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
                        "params" => ["placeholder" => "password",
                        "class" => "form-control input-no-border"], "name" => "password",
                    ],
                    ["label" => "Password"]
                )
                <input class="btn btn-rose btn-wd btn-lg" type="submit" value="Login">
                <div><a style="color: #80808094;" href="{{route('password.request')}}">Reset password</a></div>
            </div>
        </div>
    </div>
{!! Form::close() !!}

@stop
