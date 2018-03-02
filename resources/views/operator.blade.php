@extends('layouts.auth')
@section('content')
{!! Form::open(array('route' => 'redemptions.store', 'method' => 'POST')) !!}
    <div class="card card-login card-hidden">
        <div class="content">
            <div class="social-line text-center">
                Redeem offer: <br><br>
                <input type="text" name="code"><br><br>
                <input class="btn btn-rose btn-wd btn-lg" type="submit">
            </div>
        </div>
    </div>
{!! Form::close() !!}

@push('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>
@endpush

@stop
