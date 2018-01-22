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
                                    <select id="place_category" name="category_ids[]" class="formData"></select>
                                </label>
                            </p>
                            <p class="hint">Please, select the category.</p>
                        </div>
                        
                        <p><strong>Retail Type *</strong></p>
                        <div class="control-box" id="place_retailtype">
                        </div>

                        <p><strong>Specialties *</strong></p>
                        <div class="control-box" id="place_specialties">
                        </div>

                        <p><strong>Tags *</strong></p>
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
@stop

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.nau.js') }}"></script>
    <script>

        /* offer category and sub-categories */

        let formSelectCategory = document.getElementById("place_category");
        let formBoxRetailType = document.getElementById("place_retailtype");
        let formBoxSpecialties = document.getElementById("place_specialties");
        let formBoxTags = document.getElementById("place_tags");
        
        srvRequest("{{ route('categories') }}", 'GET', null, function(response){
            formSelectCategory.innerHTML = response;
            formSelectCategory.dispatchEvent(new Event('change'));
        });
        
        formSelectCategory.addEventListener('change', function(){
            let wait = '<img src="{{ asset('img/loading.gif') }}" alt="wait...">';
            formBoxRetailType.innerHTML = wait;
            formBoxSpecialties.innerHTML = wait;
            formBoxTags.innerHTML = wait;
            let url = "{{ route('categories') }}" + '/' + this.value + '?with=retailTypes;retailTypes.specialities;tags';
            srvRequest(url, 'GET', 'json', function (request){
                createRetailType(request);
                createSpecialties(request);
                createTags(request);
            });
        });
        
		function createRetailType(request) {
            let html = '';
            request.retail_types.forEach(function(e){
                html += '<p><label><input type="checkbox" name="retail_types[]" value="' + e.id + '"> ' + e.name + '</label></p>';
            });
            formBoxRetailType.innerHTML = html;
            formBoxRetailType.querySelectorAll('input').forEach(function(checkbox){
                checkbox.addEventListener('change', function(){
                    createSpecialties(request);
                });
            });
        }
        
        function createSpecialties(request) {
            let html = '';
            formBoxRetailType.querySelectorAll('input').forEach(function(checkbox){
                if (!checkbox.checked) return;
                function reatailType(e){ return e.id === checkbox.value; }
                request.retail_types.find(reatailType).specialities.forEach(function(e){
                    if (e.parent_id === checkbox.value) {
                        let type = e.group ? 'radio' : 'checkbox';
                        let name = e.group ? 'name="group' + e.group + '"' : '';
                        html += `<p><label><input type="${type}" ${name} value="${e.id}"> ${e.name}</label></p>`;
                    }
                });
            });
            formBoxRetailType.innerHTML = html ? html : 'Select Retail Type';
        }

        function createTags(request){
            let html = '';
            request.tags.forEach(function(tag){
                html += `<label><input type="checkbox" name="" value="${tag.slug}"> <span>${tag.name}</span></label>`;
            });
            formBoxTags.innerHTML = '<p>' + (html ? html : 'There is no one tag.') + '</p>';
        }


        
        
        
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

                console.dir(formData);
                return false;

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
                    error: function (resp) {
                        alert("Something went wrong. Try again, please.");
                        console.log(resp.status);
                    }
                });
            }
        });

    </script>
@endpush