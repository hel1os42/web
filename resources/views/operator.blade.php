@extends('layouts.auth')
@section('content')
<div class="card card-login card-hidden">
    <div class="content">
        <div class="social-line text-center">
            {!! Form::open(array('route' => 'redemptions.store', 'method' => 'POST', 'id' => 'formCode')) !!}
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
        </div>
    </div>
</div>


@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/login.js') }}"></script>
<script>
(function(){

    /* pin */
    document.getElementById('formCode').addEventListener('submit', function(){
        let code = this.querySelector('[name="code"]');
        code.value = code.value.toUpperCase();
    });

})();
</script>
@endpush

@stop
