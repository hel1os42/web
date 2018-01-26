@extends('layouts.master')

@section('title', 'Profile')
@section('content')
    <div class="profile">
        <div class="col-md-2">
            <div class="card card-user">
                <div class="author">
                    @if (file_exists(public_path('../storage/app/images/profile/pictures/'.$id.'.jpg')))
                        <img class="img avatar" src="{{ route('profile.picture.show') }}">
                    @else
                        <a href="#">
                            <img class="img avatar" src="{{ asset('img/avatar.png') }}">
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
                                </div>
                                <div class="col-sm-9 p-5">
                                    <p>{{ $name ?: '-' }}</p>
                                    <p>{{ $email ?: '-' }}</p>
                                    <p>{{ $phone ?: '-' }}</p>
                                    <p>{{ $invite_code }}</p>
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

                                <div class="row">
                                    @include('role-partials.selector', ['partialRoute' => 'user.show-edit'])
                                </div>
                                <div class="row">
                                    <p><input type="submit" class="btn-nau pull-right" value="Update"></p>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" id="update_photo" class="tab-pane">
                            <form method="POST" action="{{ route('users.picture.store', ['uuid' => $id]) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <h4 class="title">Update your avatar</h4>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4">
                                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                            <div class="fileinput fileinput-new text-center"
                                                 data-provides="fileinput">
                                                <div class="fileinput-new thumbnail img-circle">
                                                    <img src="{{asset('img/placeholder.jpg')}}" alt="...">
                                                </div>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail img-circle" style=""></div>
                                            <div class="btn btn-default btn-fill btn-file">
                                                <span class="fileinput-new">Pick photo</span>
                                                <span class="fileinput-exists">Change logo</span>
                                                <input type="hidden">
                                                <input type="file" name="picture">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input class="btn btn-rose btn-wd btn-md" type="submit" value="Set photo">
                            </form>
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
@endpush

@push('scripts')
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.nau.js') }}"></script>

    @include('role-partials.selector', ['partialRoute' => 'user.show-scripts'])

    <script type="text/javascript">

        /* map */

        $('a[href="#edit"]').one('shown.bs.tab', function() {
            setTimeout(function(){
                mapInit({
                    id: 'mapid',
                    done: mapDone,
                    move: mapMove
                });
            }, 100);
        });

        function mapDone(map){
            let values = mapValues(map);
        }

        function mapMove(map){
            let values = mapValues(map);
            $('[name="latitude"]').val(values.lat);
            $('[name="longitude"]').val(values.lng);
        }

    </script>
@endpush
