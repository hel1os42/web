@extends('layouts.master')

@section('title', 'Account info')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

                <div>
                    <form action="{{route('places.store')}}" method="post" class="nau-form" id="createPlaceForm" target="_top">

                        {{ csrf_field() }}

                        <p class="title">Account info</p>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Name*</span>
                                    <input name="name" value="{{old('name')}}" class="formData">
                                </label>
                            </p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Description*</span>
                                    <textarea name="description" value="{{old('description')}}" class="formData"></textarea>
                                </label>
                            </p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">About*</span>
                                    <textarea name="about" value="{{old('about')}}" class="formData"></textarea>
                                </label>
                            </p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Address*</span>
                                    <input name="address" value="{{old('address')}}" class="formData">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Offer address.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-select valid-not-empty">
                                <label>
                                    <span class="input-label">Place category*</span>
                                    <select id="place_category" name="category_ids[]" class="formData"></select>
                                </label>
                            </p>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
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
    </script>
    <script>
        $( document ).ready( function() {
        let GPS = {};
            if ( navigator.geolocation ) {
                navigator.geolocation.getCurrentPosition( getGPS, defaultGPS );
            } else {
                defaultGPS();
            }

            function getGPS( pos ) {
                GPS = {
                    lat: pos.coords.latitude,
                    lng: pos.coords.longitude
                };
                mapInitialize( GPS );
            }

            function defaultGPS() {
                /* Los Angeles: */
                GPS = {
                    lat: 34.0143733,
                    lng: -118.2831973
                };
                mapInitialize( GPS );
            }

            function mapInitialize( GPS ) {

                let map = L.map( 'mapid', {
                    center: GPS,
                    zoom:   13
                } );

                L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom:       19,
                    minZoom:       1,
                    maxNativeZoom: 18,
                    attribution:   '© OpenStreetMap',
                } ).addTo( map );


                function fillMapFields(map){
                    let radiusPx = 190;
                    $('[name="latitude"]').val(map.getCenter().lat);
                    $('[name="longitude"]').val(map.getCenter().lng);
                    $('[name="radius"]').val(Math.round(getRadius(radiusPx, map)));

                    function getRadius(radiusPx, map) {
                        return 40075016.686 * Math.abs(Math.cos(map.getCenter().lat / 180 * Math.PI)) / Math.pow(2, map.getZoom() + 8) * radiusPx;
                    }

                    function getZoom(latitude, radius) {
                        let zoom = this.round(Math.log2(40075016.686 * 75 * Math.abs(Math.cos(latitude / 180 * Math.PI)) / radius) - 8, 0.25);
                        return zoom;
                    }

                    function round(value, step) {
                        step || (step = 1.0);
                        let inv = 1.0 / step;
                        return Math.round(value * inv) / inv;
                    }
                }

                fillMapFields(map);
            }
        } );

        $("#createPlaceForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength:3,
                },
                description: {
                    required: true,
                },
                about: {
                    required: true,
                },
                address: {
                    required: true,
                }
            },
            submitHandler: function (form) {
                let formData = $('.formData').serializeArray();

                formData.push({
                    "name": "_token",
                    "value": $('[name="_token"]').val()
                });

                $.ajax({
                    type: "POST",
                    url: $(form).attr('action'),
                    headers: {
                        'Accept': 'application/json',
                    },
                    data: formData,
                    success: function (data, textStatus, xhr) {
                        if (201 == xhr.status) {
                            return window.location.replace("{{ route('profile') }}");
                        } else {
                            alert("Something went wrong. Try again, please.");
                            console.log(xhr.status);
                        }
                    },
                    error: function (resp) {
                        alert("Something went wrong. Try again, please.");
                        console.log(resp.status);
                    }
                });
            }
        });

    </script>
@endpush