@extends('layouts.auth')
@section('content')
    <div class="card card-login card-hidden">
        <div class="content">
            <div class="social-line text-center">
                {!! Form::open(array('route' => 'redemptions.store', 'method' => 'POST', 'class' => 'form-send-code')) !!}
                {{ csrf_field() }}
                @include(
                    "form.input",
                    [
                        "type" => "text",
                        "name" => "code",
                        "params" => ["class" => "form-control input-no-border", "style" => "text-transform: uppercase;"],
                    ],
                    ["label" => "Redeem offer:"]
                )
                <input class="btn btn-nau" type="submit">
                {!! Form::close() !!}
                <p><a style="color: #bbb;" href="{{ route('logout') }}">logout</a></p>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('js/login.js') }}"></script>
        <script>

            (function($){
                $('.form-send-code').on('submit', function(e) {
                    e.preventDefault();
                    let code = this.querySelector('[name="code"]');
                    code.value = code.value.trim().toUpperCase();
                    let $form = $(this);
                    let formData = $form.serializeArray();
                    $.ajax({
                        method: 'POST',
                        url: $form.attr('action'),
                        data: formData,
                        headers: { Accept: "application/json" },
                        success: function(data, textStatus, xhr) {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                alert('Success.');
                                code.value = '';
                            }
                            else alert('This code is wrong.');
                        },
                        error: function(resp){
                            alert('This code is wrong.');
                            console.log(resp);
                        }
                    });
                } );
            })( jQuery );

        </script>
    @endpush

@stop
