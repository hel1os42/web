@extends('advert.layout')

@section('title', 'Account info')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

                <div>
                    <form action="{{route('places.store')}}" method="post" class="nau-form" id="createOfferForm" target="_top">

                        {{ csrf_field() }}

                        <p class="title">Name</p>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Name</span>
                                    <input name="name" value="{{old('name')}}">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Offer name.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Description</span>
                                    <textarea name="description" value="{{old('description')}}"></textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the Offer description.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">About</span>
                                    <textarea name="about" value="{{old('about')}}"></textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the Offer description.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Address</span>
                                    <input name="address" value="{{old('address')}}">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Offer name.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-select valid-not-empty">
                                <label>
                                    <span class="input-label">Offer category</span>
                                    <select id="place_category" name="category_ids[]"></select>
                                </label>
                            </p>
                            <p class="hint">Please, select the category.</p>
                        </div>

                        <div class="control-box">
                            <p>
                                <span class="input-label"><strong>Offer picture</strong></span>
                                <label class="control-file">
                                    <span class="text-add">Add picture</span>
                                    <input name="____offer_picture" type="file" class="js-imgupload" id="offerImg">
                                    <img src="" alt="">
                                    <span class="text-hover">Drag it here</span>
                                </label>
                            </p>
                        </div>

                        <div class="control-box">
                            <p><strong>Setting map radius</strong></p>

                            <div class="map-wrap">
                                <div class="leaflet-map" id="mapid"></div>
                                <div id="marker"></div>
                            </div>
                        </div>

                        <p class="step-footer">
                            <input type="submit" class="btn-nau pull-right" value="Save">
                        </p>

                    </form>
                </div>

            </div>
        </div>
    </div>
@stop

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/datetimepicker.css') }}">
@endpush