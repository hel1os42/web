@extends('layouts.master')

@section('title', 'Edit operator')

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
            <h1>Edit Operator</h1>
            <form action="{{ route('advert.operators.update', $id) }}" method="POST" class="nau-form">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <input name="id" type="hidden" value="{{ $id }}">
                <input name="place_uuid" type="hidden" value="{{ $place_uuid }}">
                <div class="control-box">
                    <p class="control-text">
                        <label>
                            <span class="input-label">Login:</span>
                            <input name="login" value="{{ $login }}">
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
                        <input name="is_active" type="radio" id="operator_active" {{ $is_active ? 'checked' : '' }} value="1">
                        <label for="operator_active">
                            <span class="input-label">active</span>
                        </label>
                    </p>
                    <p class="control-radio-left">
                        <input name="is_active" type="radio" id="operator_deactive" {{ $is_active ? '' : 'checked' }} value="0">
                        <label for="operator_deactive">
                            <span class="input-label">deactive</span>
                        </label>
                    </p>
                </div>
                <p><input type="submit" class="btn btn-nau" value="Save"></p>
            </form>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
@endpush

@stop
