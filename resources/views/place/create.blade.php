@extends('layouts.master')

@section('title', 'Create advertiser place')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

                <div>
                    <form action="{{ route('places.store') }}" method="POST" class="nau-form" id="createPlaceForm" target="_top">
                        {{ csrf_field() }}
                        <p class="title" style="margin-top: 32px;">Create advertiser place</p>

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
                                    <span class="input-label">Description</span>
                                    <textarea name="description" class="formData">{{ old('description') }}</textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place description.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">About</span>
                                    <textarea name="about" class="formData">{!! old('about') !!}</textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the information About Place.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Address</span>
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
                        <p class="hint">Please, select Retail Type.</p>

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

        formSelectCategory.addEventListener('change', function(){
            let wait = '<img src="{{ asset('img/loading.gif') }}" alt="wait...">';
            formBoxRetailType.innerHTML = wait;
            formBoxSpecialties.innerHTML = wait;
            formBoxTags.innerHTML = wait;
            let url = "{{ route('categories') }}" + '/' + this.value + '?with=retailTypes;retailTypes.specialities;tags';
            srvRequest(url, 'GET', 'json', function (request){

                /* TODO: ВСЁ ПЕРЕДЕЛАТЬ!!!!! при изменении Retail Type не должны очищаться все галочки Spetialities!!! */
                /* но чуть позже... */

                createRetailType(request);
                createSpecialties(request);
                createTags(request);
            });
        });

        srvRequest("{{ route('categories') }}", 'GET', null, function(response){
            formSelectCategory.innerHTML = response;
            formSelectCategory.dispatchEvent(new Event('change'));
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
                let s = '';
                function reatailType(e){ return e.id === checkbox.value; }
                request.retail_types.find(reatailType).specialities.forEach(function(e){
                    if (e.retail_type_id === checkbox.value) {
                        let type = e.group ? 'radio' : 'checkbox';
                        let name = e.group ? `name="${uuid2id(e.retail_type_id)}_${e.group}"` : '';
                        s += `<p><label><input type="${type}" ${name} value="${e.slug}"> ${e.name}</label></p>`;
                    }
                });
                if (s) {
                    html += '<div class="specialities-group" data-id="' + checkbox.value + '"><p class="sgroup-title">';
                    html += checkbox.parentElement.innerText + ':</p><div class="sgroup-content">' + s + '</div></div>';
                }
            });
            formBoxSpecialties.innerHTML = html ? html : 'Select Retail Type';
        }

        function createTags(request){
            let html = '';
            request.tags.forEach(function(tag){
                html += `<label><input type="checkbox" name="" value="${tag.slug}"> <span>${tag.name}</span></label>`;
            });
            formBoxTags.innerHTML = html ? '<p>Please, select tags:</p><p>' + html + '</p>' : '<p>There is no one tag.</p>';
        }



        /* specialities accordion */
        $('#place_specialties').on('click', '.sgroup-title', function(){
           $(this).toggleClass('active').next().slideToggle();
        });

        
        
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
                    minlength :3,
                },
                description: {
                    required: true,
                },
                about: {
                    required: true,
                },
                address: {
                    required: true,
                },
            },
            submitHandler: function () {
                let $place_retailtype = $('#place_retailtype');
                if ($place_retailtype.find('input:checked').length < 1) {
                    $place_retailtype.addClass('invalid').find('input').eq(0).focus();
                    return false;
                }

                let formData = $('.formData').serializeArray();

                formData.push({
                    "name": "_token",
                    "value": $('[name="_token"]').val()
                });

                formBoxRetailType.querySelectorAll('input:checked').forEach(function(checkbox){
                    formData.push({
                        "name": "retail_types[]",
                        "value": checkbox.value
                    });
                });

                formBoxSpecialties.querySelectorAll('.specialities-group').forEach(function(group, i){
                    formData.push({
                        "name": `specialities[${i}][retail_type_id]`,
                        "value": group.dataset.id
                    });
                    group.querySelectorAll('input:checked').forEach(function(input, j){
                        formData.push({
                            "name": `specialities[${i}][specs][${j}]`,
                            "value": input.value
                        });
                    });
                });

                formBoxTags.querySelectorAll('input:checked').forEach(function(checkbox){
                    formData.push({
                        "name": "tags[]",
                        "value": checkbox.value
                    });
                });

                console.dir(formData);

                $.ajax({
                    type: "POST",
                    url: $('#createPlaceForm').attr('action'),
                    headers: { 'Accept': 'application/json' },
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