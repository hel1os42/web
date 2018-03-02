@extends('layouts.master')

@section('title', 'Create redemption')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            <h1>Redeem offer</h1>
            <form action="{{ route('redemptions.store') }}" method="POST" class="nau-form form-send-code" target="_top">
                {{ csrf_field() }}
                <div class="control-box">
                    <p class="control-text">
                        <label>
                            <span class="input-label">Code:</span>
                            <input name="code" value="">
                        </label>
                    </p>
                </div>
                <p><input type="submit" class="btn btn-nau" value="Send"></p>
            </form>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
@endpush

@push('scripts')
<script>

(function($){
    $('.form-send-code').on('submit', function(){
        let $form = $(this);
        let formData = $form.serializeArray();
        $.ajax({
            method: 'POST',
            url: $form.attr('action'),
            data: formData,
            success: function(data, textStatus, xhr){
                if (xhr.status >= 200 && xhr.status < 300){
                    location.replace(xhr.getResponseHeader('Location'));
                } else {
                    alert('This code is wrong.');
                }
            },
            error: function(resp){
                alert('This code is wrong.');
                console.log(resp);
            }
        });
    });
})(jQuery);

</script>
@endpush

@stop
