@extends('layouts.master')

@section('title', 'Edit advertiser place')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

                <div>
                    <form action="{{ route('places.store') }}" method="post" class="nau-form" id="createPlaceForm" target="_top">

                        <p class="title" style="margin-top: 32px;">Edit advertiser place</p>
                        <p class="title" style="margin-top: 32px; font-size: 48px; color: red;">DON'T WORK NOW!!!</p>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Name *</span>
                                    <input name="name" value="{{ old('name') }}" class="formData">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place name.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Description *</span>
                                    <textarea name="description" class="formData">{{ old('description') }}</textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place description.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">About *</span>
                                    <textarea name="about" class="formData">{{ old('about') }}</textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the information About Place.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Address *</span>
                                    <input name="address" value="{{ old('address') }}" class="formData">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place address.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-select valid-not-empty">
                                <label>
                                    <span class="input-label">Place category *</span>
                                    <select id="place_category" name="category" class="formData"></select>
                                </label>
                            </p>
                            <p class="hint">Please, select the category.</p>
                        </div>

                        <p><strong>Retail Type *</strong></p>
                        <div class="control-box" id="place_retailtype">
                        </div>

                        <p><strong>Specialties</strong></p>
                        <div class="control-box" id="place_specialties">
                        </div>

                        <p><strong>Tags</strong></p>
                        <div class="control-box" id="place_tags">
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
                            <p><strong>Setting map radius *</strong></p>
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

    @include('partials/place-picture-filepicker')
    <p style="color: red; visibility: hidden;">
        <strong style="color: red;">Notice! Your account will be disapproved, and all offers will be deactivated.</strong><br>
        After the positive remark verification by Admin or Agent, your account will be approved again.
    </p>

    @include('partials/place-cover-filepicker')
    <p style="color: red; visibility: hidden;">
        <strong style="color: red;">Notice! Your account will be disapproved, and all offers will be deactivated.</strong><br>
        After the positive remark verification by Admin or Agent, your account will be approved again.
    </p>

@stop

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.nau.js') }}"></script>

    <script>
        function imgError(image) {
            image.onerror = "";
            image.src = "/img/imagenotfound.svg";
            return true;
        }

        /* show notices */

        $('[type="file"]').on('change', function(){
           $(this).parents('form').next('p').css('visibility', 'visible');
        });
        $('#editPlaceForm').find('input, textarea').on('change', function(){
            $('.submit-box').css('visibility', 'visible');
        });



        /* map */

        mapInit({
            id: 'mapid',
            setPosition: {
                lat: $('[name="latitude"]').val(),
                lng: $('[name="longitude"]').val(),
                radius: $('[name="radius"]').val()
            },
            done: mapDone,
            move: mapMove
        });

        function mapDone(map){
            let values = mapValues(map);
            $('#mapradius').children('span').text(values.radius / 1000);
        }

        function mapMove(map){
            let values = mapValues(map);
            $('#mapradius').children('span').text(values.radius / 1000);
            $('[name="latitude"]').val(values.lat);
            $('[name="longitude"]').val(values.lng);
            $('[name="radius"]').val(values.radius);
            $('.submit-box').css('visibility', 'visible');
            $('#alat').text(values.lat);
            $('#alng').text(values.lng);
        }




        /* form submit */

        $('#editPlaceForm').on('submit', function (e){

            if (!confirm('Notice! Your account will be disapproved.')) return false;

            e.preventDefault();

            getFormData();
            return false;

            function getFormData(){

                let formData = $('.formData').serializeArray();

                formData.push({
                    "name" : "_token",
                    "value" : $('[name="_token"]').val()
                });

                formData.push({
                    name : "category_ids[]",
                    value : "282f495e-6f77-4a23-87b5-e1c5d924f339"
                });

                console.dir(formData);

                $.ajax({
                    type: "PATCH",
                    url: $('#editPlaceForm').attr('action'),
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
