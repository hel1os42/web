@extends('layouts.master')

@section('title', 'Account info')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

                <div>
                    <form action="{{ route('places.store') }}" method="post" class="nau-form" id="createPlaceForm" target="_top">

                        {{ csrf_field() }}

                        <p class="title">Account info</p>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Name*</span>
                                    <input name="name" value="{{old('name')}}" class="formData">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Offer name.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Description*</span>
                                    <textarea name="description" class="formData">{{ old('description') }}</textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the Offer description.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">About*</span>
                                    <textarea name="about" class="formData">{{ old('about') }}</textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the Offer description.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Address*</span>
                                    <input name="address" value="{{ old('address') }}" class="formData">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Offer name.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-select valid-not-empty">
                                <label>
                                    <span class="input-label">Place category*</span>
                                    <select id="place_category" name="category_ids[]" class="formData"></select>
                                </label>
                            </p>
                            <p class="hint">Please, select the category.</p>
                        </div>
@if(false)
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
@endif
                        <div class="control-box">
                            <p><strong>Setting map radius*</strong></p>
                            <input type="hidden" name="latitude" value="" class="mapFields formData">
                            <input type="hidden" name="longitude" value="" class="mapFields formData">
                            <input type="hidden" name="radius" value="" class="mapFields formData">
                            <div class="map-wrap">
                                <div class="leaflet-map" id="mapid"></div>
                                <div id="marker"></div>
                            </div>
                            <p id="mapradius">Radius: <span>unknown</span> km.</p>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.nau.js') }}"></script>

    <script>

        /* offer_category */

        let xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById("place_category").innerHTML = xhr.responseText;
                }
                else if (xhr.status === 400) {
                    console.log('Get categories: there was an error 400');
                }
                else {
                    console.log('Get categories: something else other than 200 was returned');
                }
            }
        };

        xhr.open("GET", "{{ route('categories') }}", true);
        xhr.send();



        /* map */

        mapInit({
            id: 'mapid',
            done: mapDone,
            move: mapMove
        });

        function mapDone(map){
            mapMove(map);
        }

        function mapMove(map){
            let values = mapValues(map);
            $('#mapradius').children('span').text(values.radius / 1000);
            $('[name="latitude"]').val(values.lat);
            $('[name="longitude"]').val(values.lng);
            $('[name="radius"]').val(values.radius);
        }



        /* form submit */

        $('#createPlaceForm').on('submit', function (e){

            e.preventDefault();

            getFormData();

            function getFormData(){

                let formData = $('.formData').serializeArray();

                formData.push({
                    "name" : "_token",
                    "value" : $('[name="_token"]').val()
                });

                $.ajax({
                    type: "POST",
                    url: $('#createPlaceForm').attr('action'),
                    headers: {
                        'Accept':'application/json',
                    },
                    data: formData,
                    success: function(data, textStatus, xhr){
                        if (201 === xhr.status){
                                return window.location.replace("{{ route('profile') }}");
                        } else {
                            alert("Something went wrong. Try again, please.");
                            console.log(xhr.status);
                        }
                    },
                    error: function(resp){
                        alert("Something went wrong. Try again, please.");
                        console.log(resp.status);
                    }
                });
            }
        });

    </script>
@endpush