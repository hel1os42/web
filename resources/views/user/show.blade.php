@extends('layouts.master')

@section('title', 'Profile')
@section('content')
    <div class="profile">
        <div class="col-md-2">
            <div class="card card-user">
                <div class="author">
                    @if (file_exists(public_path('../storage/app/images/profile/pictures/' . $id . '.jpg')))
                        <img class="img avatar" src="{{ route('users.picture.show', [$id]) }}?size=desktop">
                    @else
                        <a href="#">
                            <img class="img avatar" src="{{ asset('img/avatar.png') }}?size=desktop">
                        </a>
                    @endif
                </div>
                <div class="">
                    <h4 class="title">{{ $name }}</h4>
                    <br>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card" id="profile-main">
                <div class="content">
                    <div class="nav-tabs-navigation">
                        <div class="nav-tabs-wrapper">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                <li class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true">Profile info</a></li>
                                <li><a href="#edit" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true">Edit profile</a></li>
                                <li class=""><a href="#update_photo" aria-controls="update_photo" role="tab" data-toggle="tab" aria-expanded="false">Update photo</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <div class="row">
                                <div class="col-sm-3 p-5">
                                    <p><strong>Name</strong></p>
                                    <p><strong>Email</strong></p>
                                    <p><strong>Phone</strong></p>
                                    <p><strong>Invite code</strong></p>
                                    <p>
                                        <strong>
                                            {{ __('users.fields.points') }}
                                        </strong>
                                    </p>
                                    <p>
                                        <strong>
                                            {{ __('users.fields.last_logged_in_at') }}
                                        </strong>
                                    </p>
                                </div>
                                <div class="col-sm-9 p-5">
                                    <p>{{ $name ?: '-' }}</p>
                                    <p>{{ $email ?: '-' }}</p>
                                    <p>{{ $phone ?: '-' }}</p>
                                    <p>{{ $invite_code }}</p>
                                    <p>
                                        {{ $points }}
                                    </p>
                                    <p>
                                        {{ $last_logged_in_at
                                            ? Carbon\Carbon::parse($last_logged_in_at)->format('Y-m-d H:i') . ' (UTC)'
                                            : '-'
                                         }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                @include('role-partials.selector', ['partialRoute' => 'user.show'])
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="edit">
                            <form action="{{ route('users.update', $id) }}" method="POST" enctype="application/x-www-form-urlencoded">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="row">

                                    <div class="col-sm-3 p-5">
                                        <p><strong>Name</strong></p>
                                        <p><strong>Email</strong></p>
                                        <p><strong>Phone</strong></p>
                                    </div>
                                    <div class="col-sm-9 p-5">
                                        <p><label><input style="line-height: 14px; font-size: 14px;" name="name" value="{{ $name }}"></label></p>
                                        <p><label><input style="line-height: 14px; font-size: 14px;" name="email" value="{{ $email }}"></label></p>
                                        <p><label><input style="line-height: 14px; font-size: 14px;" name="phone" value="{{ $phone }}"></label></p>
                                    </div>

                                </div>

                                @if(false)
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <p><strong>Position</strong></p>
                                            <div class="map-wrap" style="width: 400px;">
                                                <div id="mapid" style="height: 400px; width: 600px;">
                                                    <div id="marker" class="without-radius"></div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="latitude" value="{{ $latitude }}">
                                            <input type="hidden" name="longitude" value="{{ $longitude }}">
                                        </div>
                                    </div>
                                @endif
                                <input type="hidden" name="latitude" value="{{ $latitude }}">
                                <input type="hidden" name="longitude" value="{{ $longitude }}">

                                @include('role-partials.selector', ['partialRoute' => 'user.show-edit'])

                                <div class="row">
                                    <p><input type="submit" class="btn-nau pull-right" value="Update"></p>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" id="update_photo" class="tab-pane">
                            <h4 class="title">Update your avatar</h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <form method="POST" action="{{ route('users.picture.store', ['uuid' => $id]) }}" enctype="multipart/form-data">
                                        <div class="form-group" id="userpic_image_box">
                                            {{ csrf_field() }}
                                            <div class="image-box" data-maxsize="2097152"></div>
                                        </div>
                                        <input class="btn btn-rose btn-wd btn-md" type="submit" value="Set photo">
                                        <p class="image-example" style="padding-top:20px; color: #999; font-size: 80%;">
                                            Image requirements:<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<em>format: jpg/jpeg, png</em><br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<em>maximum size: 2 Mb</em><br>
                                            Recommended image:<br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<em>your face</em><br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<em>up to 1024 x 1024 px</em>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/cropper/imageuploader.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/cropper/cropper.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.nau.js') }}"></script>
    <script src="{{ asset('js/cropper/imageuploader.js') }}"></script>
    <script src="{{ asset('js/cropper/cropper.js') }}"></script>

    <script>

        /* approve/disapprove buttons */
        userStatusControl();

        /* userpic */
        imageUploader('#userpic_image_box .image-box');
        let $userpic_image_box = $('#userpic_image_box');
        $userpic_image_box.find('[type="file"]').on('change', function(){
            $(this).attr('data-changed', 'true');
            console.log('Picture changed');
            $userpic_image_box.find('.image').attr('data-cropratio', '1').attr('data-circle', 'true');
        });
        $userpic_image_box.find('.image').attr('src', $('.avatar').attr('src')).on('load', function(){
            $(this).parents('.img-hide').removeClass('img-hide');
            if (this.dataset.cropratio) {
                imageCropperRemove(this);
                imageCropperInit(this);
            }
        });
        $userpic_image_box.parents('form').on('submit', function(e){
            e.preventDefault();
            let $file = $userpic_image_box.find('[type="file"]');
            let $img = $userpic_image_box.find('.image');
            if ($file.attr('data-changed') && $img.attr('data-crop')) {
                let url = $(this).attr('action');
                let formData = new FormData();
                formData.append('_token', $userpic_image_box.find('[name="_token"]').val());
                let base64Data = imageCropperCrop($img.get(0)).getAttribute('src').replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
                formData.append('picture', base64toBlob(base64Data, 'image/jpeg'), 'picture.jpg');
                for(let i of formData) { console.log(i); }
                $.ajax({
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    success: function () {
                        console.log('SUCCESS: image sent.');
                        window.location.reload();
                    },
                    error: function (resp) {
                        if (401 === resp.status) UnAuthorized();
                        else if (0 === resp.status) AdBlockNotification();
                        else {
                            console.log('ERROR: image not sent.');
                            console.dir(resp);
                        }
                    }
                });
            }
        });

        /* map */
        /*$('a[href="#edit"]').one('shown.bs.tab', function() {
            setTimeout(function(){
                mapInit({
                    id: 'mapid',
                    //done: mapDone,
                    move: mapMove
                });
            }, 100);
        });

        function mapMove(map){
            let values = mapValues(map);
            $('[name="latitude"]').val(values.lat);
            $('[name="longitude"]').val(values.lng);
        }*/

        function userStatusControl(){
            $('.user-approve-controls form').on('submit', function(e){
                e.preventDefault();

                let $box = $(this).parents('.user-approve-controls');
                let $user_status = $box.find('[name="approved"]');
                let $err = $box.find('.waiting-response');

                $box.removeClass('status-approved status-disapproved').addClass('status-wait');
                let formData = $(this).serializeArray();
                console.log('Change User Status:');
                console.dir(formData);

                $.ajax({
                    method: "PATCH",
                    url: $(this).attr('action'),
                    headers: { 'Accept':'application/json' },
                    data: formData,
                    success: function(data, textStatus, xhr){
                        if (201 === xhr.status){
                            $box.removeClass('status-wait').addClass('status-' + ($user_status.val() === '0' ? 'dis' : '') + 'approved');
                            $user_status.val($user_status.val() === '0' ? '1' : '0');
                        } else {
                            $err.text('err-st: ' + xhr.status);
                            console.dir(xhr);
                        }
                    },
                    error: function(resp){
                        if (401 === resp.status) UnAuthorized();
                        else if (0 === resp.status) AdBlockNotification();
                        else {
                            $err.text('err-st: ' + resp.status);
                            console.dir(resp);
                            alert(`Error ${resp.status}: ${resp.responseText}`);
                        }
                    }
                });
            });
        }

    </script>
@endpush
