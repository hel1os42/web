@extends('layouts.auth')
@section('content')
{!! Form::open(array('route' => 'login', 'method' => 'POST', 'class' => 'huyeta')) !!}
    <div class="card card-login card-hidden">
        <div class="header text-center">
            <h3 class="title">Login</h3>
        </div>
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
                <input class="btn btn-rose btn-wd btn-lg" type="submit">
            </div>
        </div>
    </div>
{!! Form::close() !!}

@stop
