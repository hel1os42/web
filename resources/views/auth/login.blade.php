@extends('layouts.auth')
@section('content')

<div class="card card-login card-hidden">
    <div class="content">
        <div class="social-line text-center">

            <div>
                {!! Form::open(array('route' => 'login', 'method' => 'POST')) !!}
                    @include(
                        "form.input",
                        [
                            "type" => "email",
                            "name" => "email",
                            "params" => ["placeholder" => "email", "class" => "form-control input-no-border"],
                        ],
                        ["label" => "Email"]
                    )
                    @include(
                        "form.input",
                        [
                            "type" => "password",
                            "name" => "password",
                            "params" => ["placeholder" => "password", "class" => "form-control input-no-border"],

                        ],
                        ["label" => "Password"]
                    )
                    <input class="btn btn-nau" type="submit" value="Login">
                {!! Form::close() !!}
                <div><a style="color: #bbb;" href="{{ route('password.request') }}">Reset password</a></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/login.js') }}"></script>
@endpush

@stop
