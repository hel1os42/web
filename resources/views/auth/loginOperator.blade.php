@extends('layouts.auth')
@section('content')

<div class="card card-login card-hidden">
    <div class="content">
        <div class="social-line text-center">

            <ul class="tabs-titles">
                <li class="active"><a href="#">Operator</a></li>
            </ul>
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

@push('styles')
    <style>
        .login-page .card { padding-top: 0; padding-bottom: 6px; }
        .tabs-titles { position: relative; z-index: 2; margin: 0; padding: 0; list-style: none; }
        .tabs-titles:after { content: ""; display: block; clear: both; }
        .tabs-titles li { float: left; }
        .tabs-titles li a { position: relative; display: block; padding: 6px 15px; color: #364150; border: 1px solid #eee; border-bottom: none; border-radius: 6px 6px 0 0;}
        .tabs-titles li.active a { color: #fff; background: #f08301 linear-gradient(to right, #f08301, #f0a810); }
        .tabs-titles li.active a:after { content: ''; position: absolute; left: 0; top: 100%; right: 0; height: 1px; background: #f08301 linear-gradient(to right, #f08301, #f0a810); }
        .tabs-titles li:not(:first-child) { margin-left: -1px; }
        .tabs .tab { display: none; padding: 12px 15px; border: 1px solid #eee; border-radius: 0 0 6px 6px; }
        .tabs .tab.active { display: block; }
        #formOperator { margin-bottom: 6px; }
    </style>
@endpush

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/login.js') }}"></script>
<script>
(function(){

    /* tabs */
    let tabsTitles = document.querySelectorAll('.tabs-titles a');
    let tabs = document.querySelectorAll('.tabs .tab');
    document.querySelector(document.querySelector('.tabs-titles .active a').getAttribute('href')).classList.add('active');
    tabsTitles.forEach(function(link){
        link.onclick = function(e){
            e.preventDefault();
            tabsTitles.forEach(function(link){ link.parentElement.classList.remove('active'); });
            tabs.forEach(function(tab){ tab.classList.remove('active'); });
            this.parentElement.classList.add('active');
            document.querySelector(this.getAttribute('href')).classList.add('active');
        };
    });

})();
</script>
@endpush

@stop
