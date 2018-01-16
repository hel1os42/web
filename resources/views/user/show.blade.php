@extends('layouts.master')

@section('title', 'Profile')
@section('content')
    <div class="profile">
        <div class="col-md-2">
            <div class="card card-user">
                <div class="author">
                    @if (file_exists(public_path('../storage/app/images/profile/pictures/'.$id.'.jpg')))
                        <img class="img avatar" src="{{route('profile.picture.show')}}">
                    @else
                        <a href="#">
                            <img class="img avatar" src="{{asset('img/avatar.png')}}">
                        </a>
                    @endif
                </div>
                <div class="">
                    <h4 class="title">{{$name}}</h4>
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
                                <li class="active"><a href="#profile" aria-controls="profile" role="tab"
                                                      data-toggle="tab" aria-expanded="true">Profile info</a>
                                </li>
                                <li><a href="#edit" aria-controls="profile" role="tab" data-toggle="tab"
                                       aria-expanded="true">Edit profile</a>

                                <li class=""><a href="#update_photo" aria-controls="update_photo" role="tab"
                                                data-toggle="tab" aria-expanded="false">Update photo</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <div class="row">
                                <div class="col-sm-6 p-5">
                                    <p><strong>Name</strong></p>
                                    <p><strong>Email</strong></p>
                                    <p><strong>Phone</strong></p>
                                    <p><strong>Invite code</strong></p>
                                </div>
                                <div class="col-sm-6 p-5">
                                    <p>{{$name ?: '-'}}</p>
                                    <p>{{$email ?: '-'}}</p>
                                    <p>{{$phone ?: '-'}}</p>
                                    <p>
                                        {{$invite_code}}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                @include('role-partials.selector', ['partialRoute' => 'user.show'])
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="edit">
                            <form action="{{route('users.update', $id)}}" method="POST"
                                  enctype="application/x-www-form-urlencoded">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="row">

                                    <div class="col-sm-6 p-5">
                                        <p><strong>Name</strong></p>
                                        <p><strong>Email</strong></p>
                                        <p><strong>Phone</strong></p>
                                        <p><strong>Position</strong></p>
                                    </div>
                                    <div class="col-sm-6 p-5">

                                        <p><input style="line-height: 14px; font-size: 14px;" type="text" name="name"
                                                  value="{{$name}}"></p>
                                        <p><input style="line-height: 14px; font-size: 14px;" type="text" name="email"
                                                  value="{{$email}}"></p>
                                        <p><input style="line-height: 14px; font-size: 14px;" type="text" name="phone"
                                                  value="{{$phone}}"></p>
                                        <div class="map-wrap" style="width: 400px;">
                                            <div id="mapid" style="height: 400px; width: 600px;">
                                                <div id="marker" style="z-index: 500;"></div>
                                            </div>

                                        </div>
                                        <input type="hidden" name="latitude" value="{{$latitude}}">
                                        <input type="hidden" name="longitude" value="{{$longitude}}">
                                    </div>

                                </div>
                                <div class="row">
                                    @include('role-partials.selector', ['partialRoute' => 'user.show-edit'])
                                </div>
                                <div class="row">
                                    <button type="submit" class="pull-right">Update</button>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" id="update_photo" class="tab-pane">
                            <form method="POST" action="{{route('profile.picture.store')}}"
                                  enctype="multipart/form-data">
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
                                            <div class="fileinput-preview fileinput-exists thumbnail img-circle"
                                                 style=""></div>
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

    @include('role-partials.selector', ['partialRoute' => 'user.show-scripts'])

    <script type="text/javascript">
        $( document ).ready( function() {

            let mapContainer = {
                gps:                {},
                zoom:               13,
                defaultZoom:        1,
                map:                null,
                mapIdSelector:      'mapid',
                markerRadius:       190,
                form:               {
                    lat:    $( '[name="latitude"]' ),
                    lng:    $( '[name="longitude"]' ),
                    radius: $( '[name="radius"]' ),
                },
                run:                function() {
                    this.copyFromFormToMap();
                    if ( this.map === null && $.isEmptyObject( this.gps ) ) {
                        this.getCurrentPosition();
                        return;
                    }
                    if ( this.map === null ) {
                        this.startMap();
                    }
                },
                getCurrentPosition: function() {
                    if ( navigator.geolocation ) {
                        navigator.geolocation.getCurrentPosition( this.getGps );
                    }

                },
                getGps:             function( pos ) {
                    let gps = {
                        lat: pos.coords.latitude,
                        lng: pos.coords.longitude
                    };
                    passGpsToMapContainer( gps );
                },
                startMap:           function() {
                    this.map = L.map( this.mapIdSelector, {
                        center: this.gps,
                        zoom:   this.zoom
                    } );

                    L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom:       19,
                        minZoom:       1,
                        maxNativeZoom: 18,
                        attribution:   '© OpenStreetMap',
                    } ).addTo( this.map );

                    this.copyFromMapToForm();

                    $( this.map ).on( 'zoomend, moveend', this.copyFromMapToForm() );

                },
                copyFromMapToForm:  function() {
                    $( this.form.lat ).val( this.map.getCenter().lat );
                    $( this.form.lng ).val( this.map.getCenter().lng );
                    $( this.form.radius ).val( Math.round( getRadius( this.markerRadius, this.map ) ) );

                    function getRadius( radiusPx, map ) {
                        return 40075016.686 * Math.abs( Math.cos( map.getCenter().lat / 180 * Math.PI ) ) / Math.pow(
                            2, map.getZoom() + 8 ) * radiusPx;
                    }

                    function getZoom( latitude, radius ) {
                        let zoom = this.round( Math.log2( 40075016.686 * 75 * Math.abs(
                            Math.cos( latitude / 180 * Math.PI ) ) / radius ) - 8, 0.25 );
                        return zoom;
                    }

                    function round( value, step ) {
                        step || (step = 1.0);
                        let inv = 1.0 / step;
                        return Math.round( value * inv ) / inv;
                    }
                },
                setGps:             function( gps ) {
                    this.gps = gps;
                    return this;
                },
                copyFromFormToMap:  function() {
                    let lat = Number( $( this.form.lat ).val() );
                    let lng = Number( $( this.form.lng ).val() );
                    if ( lat !== 0 && lng !== 0 ) {
                        this.setGps( {
                            lat: lat,
                            lng: lng
                        } )
                    }
                }
            };

            function passGpsToMapContainer( gps ) {
                mapContainer.setGps( gps ).startMap();
            }

            $( 'a[href="#edit"]' ).on( 'click', function() {
                mapContainer.run();
            } );
        } );

    </script>
@endpush
