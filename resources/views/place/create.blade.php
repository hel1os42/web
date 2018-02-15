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
                                    <input name="name" value="" class="formData" data-max-length="30">
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place name.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Description</span>
                                    <textarea name="description" class="formData" data-max-length="100"></textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the Place description.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">About</span>
                                    <textarea name="about" class="formData"></textarea>
                                </label>
                            </p>
                            <p class="hint">Please, enter the information About Place.</p>
                        </div>

                        <div class="control-box">
                            <p class="control-text">
                                <label>
                                    <span class="input-label">Address</span>
                                    <input name="address" value="" class="formData">
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

                        @include('partials/place-picture-filepicker')

                        @include('partials/place-cover-filepicker')

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

                        <div class="control-box">
                            <div class="row gps-crd-box">
                                <div class="col-xs-10">
                                    <p class="control-text">
                                        <label>
                                            <span class="input-label">Address or GPS</span>
                                            <input name="gps_crd" value="">
                                        </label>
                                    </p>
                                </div>
                                <div class="col-xs-2">
                                    &nbsp;<br>
                                    <span class="btn" id="btn_gps_crd">Go</span>
                                </div>
                            </div>
                            <p class="hint">Invalid address or GPS coordinates: object not found.</p>
                            <p class="address-examples">
                                Examples of address:<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<em>6931 Atlantic LA CA</em><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<em>Australia, Melbourne, Peate Ave, 16</em><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<em>Львів, Кобиляньської 16</em><br><br>
                                Example of GPS coordinates:<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<em>49.4213687,26.9971402</em>
                            </p>
                        </div>

                        <p class="clearfix"><input type="submit" class="btn-nau pull-right" value="Create Place"></p>

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
    <script src="{{ asset('js/formdata.min.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.nau.js') }}"></script>
    <script>

        let redirectUrl;

        /* offer category and sub-categories */

        let formSelectCategory = document.getElementById("place_category");
        let formBoxRetailType = document.getElementById("place_retailtype");
        let formBoxSpecialties = document.getElementById("place_specialties");
        let formBoxTags = document.getElementById("place_tags");
        let spetialitiesCache = {};

        formSelectCategory.addEventListener('change', function(){
            let wait = '<img src="{{ asset('img/loading.gif') }}" alt="wait...">';
            formBoxRetailType.innerHTML = wait;
            formBoxSpecialties.innerHTML = wait;
            formBoxTags.innerHTML = wait;
            let url = "{{ route('categories') }}" + '/' + this.value + '?with=retailTypes;retailTypes.specialities;tags';
            srvRequest(url, 'GET', 'json', function (response){
                createRetailType(response);
                createSpecialties(response);
                createTags(response);
            });
        });

        srvRequest("{{ route('categories') }}", 'GET', null, function(response){
            formSelectCategory.innerHTML = response;
            formSelectCategory.dispatchEvent(new Event('change'));
        });

        /* you can not input more than N characters in this fields */
        setFieldLimit('[data-max-length]');

        function createRetailType(request) {
            let html = '';
            request.retail_types.forEach(function(e){
                html += '<p><label><input type="checkbox" name="retail_types[]" value="' + e.id + '"> ' + e.name + '</label></p>';
            });
            formBoxRetailType.innerHTML = html;
            formBoxRetailType.querySelectorAll('input').forEach(function(checkbox){
                checkbox.addEventListener('change', function(){
                    if (!spetialitiesCache[this.value]) spetialitiesCache[this.value] = {};
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
                        let checked = spetialitiesCache[e.retail_type_id][e.slug] ? 'checked' : '';
                        s += `<p><label><input type="${type}" ${name} value="${e.slug}" ${checked}> ${e.name}</label></p>`;
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
        }).on('change', 'input', function(){
            let uuid = $(this).parents('.specialities-group').attr('data-id');
            if ($(this).is('[type="checkbox"]')) {
                if ($(this).prop('checked')) spetialitiesCache[uuid][$(this).val()] = true;
                else delete spetialitiesCache[uuid][$(this).val()];
            } else {
                $(`[name="${$(this).attr('name')}"]`).not(':checked').each(function(){
                    delete spetialitiesCache[uuid][$(this).val()];
                }).end().filter(':checked').each(function(){
                    spetialitiesCache[uuid][$(this).val()] = true;
                });
            }
        });

        
        
        /* map */

        mapInit({
            id: 'mapid',
            done: mapDone,
            move: mapMove
        });

        function mapDone(map){
            mapMove(map);
            /* set map position by GPS or Address */
            setMapPositionByGpsOrAddress(map);
        }

        function mapMove(map){
            let values = mapValues(map);
            $('#mapradius').children('span').text(values.radius / 1000);
            $latitude = $('[name="latitude"]');
            $longitude = $('[name="longitude"]');
            $latitude.val(values.lat);
            $longitude.val(values.lng);
            $('[name="radius"]').val(values.radius);
            $('[name="gps_crd"]').val($latitude.val() + ', ' + $longitude.val());
        }



        /* picture and cover */

        let $place_picture_box = $('#place_picture_box');
        let $place_cover_box = $('#place_cover_box');
        $place_picture_box.find('[type="file"]').on('change', function(){
            $(this).attr('data-changed', 'true');
            console.log('Picture changed');
        });
        $place_cover_box.find('[type="file"]').on('change', function(){
            $(this).attr('data-changed', 'true');
            console.log('Cover changed');
        });




        /* form submit */

        $("#createPlaceForm").on('submit', function(e){
            e.preventDefault();

            if (!formValidation()) return false;

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

            waitPopup(false);

            $.ajax({
                type: "POST",
                url: $('#createPlaceForm').attr('action'),
                headers: { 'Accept': 'application/json' },
                data: formData,
                success: function(data, textStatus, xhr){
                    if (201 === xhr.status){
                        redirectUrl = xhr.getResponseHeader('Location');
                        sendImages();
                    } else {
                        $('#waitError').text('Status: ' + xhr.status);
                        console.log("Something went wrong. Try again, please.");
                        console.log(xhr.status);
                    }
                },
                error: function (resp) {
                    $('#waitError').text(`Error ${resp.status}: ${resp.responseText}`);
                    console.log("Something went wrong. Try again, please.");
                    console.log(resp.status);
                }
            });
        });

        function formValidation(){
            let res = true;
            let $place_retailtype = $('#place_retailtype');
            if ($place_retailtype.find('input:checked').length < 1) {
                $place_retailtype.addClass('invalid').find('input').eq(0).focus();
                res = false;
            }
            let $place_name = $('[name="name"]');
            if ($place_name.val().length < 3) {
                $place_name.focus().parents('.control-text').addClass('invalid');
                res = false;
            }
            return res;
        }

        function sendImages(){
            let n = { count: 0 };
            let isNewPicture = $place_picture_box.find('[type="file"]').attr('data-changed');
            let isNewCover = $place_cover_box.find('[type="file"]').attr('data-changed');
            if (isNewPicture) n.count++;
            if (isNewCover) n.count++;
            redirectPage(n);
            if (isNewPicture) sendImage(n, $place_picture_box, "{{ route('place.picture.store') }}", redirectPage);
            if (isNewCover) sendImage(n, $place_cover_box, "{{ route('place.cover.store') }}", redirectPage);
        }

        function redirectPage(n){
            if (n.count === 0) {
                //window.location.replace("{{ route('profile') }}");
                window.location.replace(redirectUrl);
            }
        }

        function sendImage(n, $box, URI, callback){
            let formData = new FormData();
            formData.append('_token', $box.find('[name="_token"]').val());
            formData.append('picture', $box.find('[type="file"]').get(0).files[0]);
            for(let i of formData) { console.log(i); }
            $.ajax({
                url: URI,
                data: formData,
                processData: false,
                contentType: false,
                method: 'POST',
                success: function () {
                    console.log('SUCCESS:', URI);
                    n.count -= 1;
                    callback(n);
                },
                error: function (resp) {
                    $('#waitError').text(resp.status);
                    console.log('Error:', URI);
                }
            });
        }

        function setMapPositionByGpsOrAddress(map){
            /* TODO: нужен рефакторинг, копия кода на 4-х страницах */
            let $country = $('[name="country"]');
            let $city = $('[name="city"]');
            let $gps_crd = $('[name="gps_crd"]');
            setCountryCity();
            $country.add($city).on('blur', function(){ setCountryCity(); });
            $('#btn_gps_crd').on('click', function(){
                let address = $gps_crd.val().trim();
                if (address.length < 5) return false;
                address = tryConvertToGPS(address);
                if (address.lat) {
                    map.panTo(address);
                    mapMove(map);
                } else {
                    getGpsByAddress(address, function(response){
                        if (response.results.length) {
                            map.panTo(response.results[0].geometry.location);
                            mapMove(map);
                        } else {
                            $gps_crd.parents('.gps-crd-box').addClass('invalid');
                        }
                    });
                }
            });
            function setCountryCity(){
                let country = $country.val();
                let city = $city.val();
                let str = (country ? country + ', ' : '') + (city ? city + ', ' : '');
                $gps_crd.val(str);
            }
            function tryConvertToGPS(str){
                let arr = str.split(/,\s*/);
                if (arr.length !== 2) return str;
                let lat = parseFloat(arr[0]);
                let lng = parseFloat(arr[1]);
                if (isNaN(lat) || isNaN(lng)) return str;
                return {lat, lng};
            }
        }

    </script>
@endpush