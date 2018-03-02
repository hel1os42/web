@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')

@if(false)
    @if(!empty($errors->first()))
        <div class="row col-lg-12">
            <div class="alert alert-danger">
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    @endif
@endif

<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            <h1>Create Operator</h1>
            <form action="{{ route('advert.operators.store') }}" method="POST" target="_top" class="nau-form">
                {{ csrf_field() }}
                <input name="place_uuid" type="hidden" value="{{ $place_uuid }}">
                <p>You'r ID place &mdash; <strong>{{ $place_uuid }}</strong></p>
                <div class="control-box">
                    <p class="control-text">
                        <label>
                            <span class="input-label">Login:</span>
                            <input name="login" value="{{ old('login') }}">
                        </label>
                    </p>
                </div>
                <div class="control-box">
                    <p class="control-text">
                        <label>
                            <span class="input-label">Pin:</span>
                            <input name="password" type="password" value="">
                        </label>
                    </p>
                </div>
                <div class="control-box">
                    <p class="control-text">
                        <label>
                            <span class="input-label">Confirm pin:</span>
                            <input name="confirm" type="password" value="">
                        </label>
                    </p>
                </div>
                <div class="control-box">
                    <p class="control-radio-left">
                        <input name="is_active" type="radio" id="operator_active" value="1">
                        <label for="operator_active">
                            <span class="input-label">active</span>
                        </label>
                    </p>
                    <p class="control-radio-left">
                        <input name="is_active" type="radio" id="operator_deactive" checked value="0">
                        <label for="operator_deactive">
                            <span class="input-label">deactive</span>
                        </label>
                    </p>
                </div>
                <p><input type="submit" class="btn btn-nau" value="Create operator"></p>
            </form>
        </div>
    </div>
</div>

@push('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
@endpush

@stop
