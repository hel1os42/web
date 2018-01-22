@extends('layouts.master')

@section('title', 'Place Information')

@section('content')
<div class="col-md-10 col-md-offset-1">
    <div class="card">
        <div class="content">

            <h3>Place Information</h3>

            <form action="{{ route('places.update', $id) }}" method="PATCH" class="nau-form" id="editPlaceForm" target="_top">

                {{ csrf_field() }}

                <table id="table_your_offers" class="display">
                    <tr>
                        <td width="140"><label for="formFieldName">Name</label></td>
                        <td class="details-control">
                            <p><input name="name" id="formFieldName" value="{{ $name }}" class="formData"></p>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="formFieldDescription">Description</label></td>
                        <td class="details-control">
                            <p><textarea name="description" id="formFieldDescription" class="formData">{{ $description }}</textarea></p>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="formFieldAbout">About</label></td>
                        <td class="details-control">
                            <p><textarea name="about" id="formFieldAbout" class="formData">{{ $about }}</textarea></p>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="formFieldAddress">Address</label></td>
                        <td class="details-control">
                            <p><input name="address" id="formFieldAddress" value="{{ $address }}" class="formData"></p>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="formFieldAddress">Place category</label></td>
                        <td class="details-control">
                            <div class="control-box">
                                <p class="control-select valid-not-empty">
                                    <label>
                                        <select id="place_category" name="category_ids[]" class="formData"></select>
                                    </label>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Picture</td>
                        <td class="details-control">
                            <div class="img-container text-center">
                                <p><img src="{{ route('places.picture.show', [$id, 'picture']) }}" onerror="imgError(this);"></p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Cover</td>
                        <td class="details-control">
                            <div class="img-container text-center">
                                <p><img src="{{ route('places.picture.show', [$id, 'cover']) }}" onerror="imgError(this);"></p>
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="control-box">
                    <p><strong>Map radius</strong></p>
                    <input type="hidden" name="latitude" value="{{ $latitude }}" class="mapFields formData">
                    <input type="hidden" name="longitude" value="{{ $longitude }}" class="mapFields formData">
                    <input type="hidden" name="radius" value="{{ $radius }}" class="mapFields formData">
                    <div class="map-wrap" style="max-width: 540px;">
                        <div class="leaflet-map" id="mapid"></div>
                        <div id="marker"></div>
                    </div>
                    <p id="mapradius">Radius: <span>unknown</span> km.</p>
                    <p><span id="alat"></span> <span id="alng"></span></p>
                </div>

                <div class="submit-box" style="visibility: hidden">
                    <p style="color: red;">
                        <strong style="color: red;">Notice! Your account will be disapproved, and all offers will be deactivated.</strong><br>
                        After the positive remark verification by Admin or Agent, your account will be approved again.
                    </p>
                    <!--<p><input type="submit" class="btn-nau" value="Save"></p>-->
										<p><strong style="font-size: 36px;">[ Save ]</strong> Sorry, this function was deactivated. Need Place Category.</p>
                </div>

            </form>

            <br><br><br>

@if(false)
            <!-- Что это за пустая ссылка? -->
            <a href="{{route('profile.place.offers')}}"></a>
@endif

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
