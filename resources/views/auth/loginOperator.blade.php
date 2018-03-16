@extends('layouts.auth')
@section('content')

<div class="card card-login card-hidden">
    <div class="content">
        <div class="social-line text-center">
            <div>
                {!! Form::open(array('route' => 'login', 'method' => 'POST', 'id'=>'formOperator')) !!}
                    @include(
                        "form.input",
                        [
                            "type" => "text",
                            "name" => "alias",
                            "params" => ["placeholder" => "alias", "class" => "form-control input-no-border"],
                        ],
                        ["label" => "Alias"]
                    )
                    @include(
                        "form.input",
                        [
                            "type" => "text",
                            "name" => "login",
                            "params" => ["placeholder" => "login", "class" => "form-control input-no-border"],
                        ],
                        ["label" => "Login"]
                    )
                    @include(
                        "form.input",
                        [
                            "type" => "password",
                            "name" => "pin",
                            "params" => ["placeholder" => "pin", "class" => "form-control input-no-border"],
                        ],
                        ["label" => "Pin"]
                    )
                    <input class="btn btn-nau" type="submit" value="Login">
                {!! Form::close() !!}
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
