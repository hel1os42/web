@extends('layouts.master')

@section('title', 'Profile')
@php
    /** @var \App\Models\User $user */
        $user = \App\Models\User::query()->find($id);
        $roleIds = array_column(\App\Models\Role::query()->get(['id'])->toArray(), 'id');
        $children = $user->children->toArray();


        if(auth()->user()->isAdmin()) {
            $allChildren = \App\Models\User::query()->get();
        } else {
            $allChildren = auth()->user()->children;
        }

        $allPossibleChildren = [];


        if($user->isAgent()) {
            $rolesForChildSet = [\App\Models\Role::ROLE_CHIEF_ADVERTISER, \App\Models\Role::ROLE_ADVERTISER];
        } else {
            $rolesForChildSet = [\App\Models\Role::ROLE_ADVERTISER];
        }

            foreach ($allChildren as $childValue) {
                if($childValue->hasRoles($rolesForChildSet)) {
                    $allPossibleChildren[] = $childValue->toArray();
                }
            }
        $roles = isset($roles) ? $roles : [];
@endphp
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
                                @if($id === $authUser['id'])
                                    <li class=""><a href="#update_photo" aria-controls="update_photo" role="tab"
                                                    data-toggle="tab" aria-expanded="false">Update photo</a>
                                    </li>
                                @endif
                                @if(false)
                                    <li class=""><a href="#find_offers" aria-controls="offers" role="tab"
                                                    data-toggle="tab"
                                                    aria-expanded="false">Find offers</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <div class="row">
                                <div class="col-sm-2 p-5">
                                    <p><strong>Name</strong></p>
                                    <p><strong>Id</strong></p>
                                    <p><strong>Email</strong></p>
                                    <p><strong>Phone</strong></p>
                                    <p><strong>Approved</strong></p>
                                    <p><strong>Invite link</strong></p>
                                </div>
                                <div class="col-sm-6 p-10 p-5">
                                    <p>{{$name}}</p>
                                    <p>{{$id}}</p>
                                    <p>{{$email}}</p>
                                    <p>{{$phone}}</p>
                                    <div>
                                        @if($approved)
                                            <p style="color:green">Yes</p>
                                        @else
                                            No
                                            @can('users.update', [$user, ['approved' => true]])
                                                <form action="{{route('users.update', $id)}}" method="post"
                                                      style="display:  inline-block;">
                                                    {{ csrf_field() }}
                                                    {{ method_field('PATCH') }}
                                                    <input hidden type="text" name="approved" value="1">
                                                    <button style="display:  inline-block;" type="submit">approve
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                    </div>
                                    <p>
                                        {{route('registerForm', $invite_code)}}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="edit">
                            @if (isset($errors))
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endif
                            <form action="{{route('users.update', $id)}}" method="POST"
                                  enctype="application/x-www-form-urlencoded">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="row">
                                    <div class="col-sm-6 p-5">
                                        <p><strong>Id</strong></p>
                                        <p><strong>Name</strong></p>
                                        <p><strong>Email</strong></p>
                                        <p><strong>Phone</strong></p>
                                        <p><strong>Position</strong></p>
                                        @can('user.update.roles', [$user, $roleIds])
                                            <p style="height: 120px;"><strong>Roles</strong></p>
                                        @endcan
                                        @can('user.update.children', [$user, array_column($allPossibleChildren, 'id')])
                                            <p><strong>Set children</strong></p>
                                        @endcan
                                    </div>
                                    <div class="col-sm-6 p-10 p-5">
                                        <p style="line-height: 14px; font-size: 14px;">{{$id}}</p>
                                        <p><input style="line-height: 14px; font-size: 14px;" type="text" name="name"
                                                  value="{{$name}}"></p>
                                        <p><input style="line-height: 14px; font-size: 14px;" type="text" name="email"
                                                  value="{{$email}}"></p>
                                        <p><input style="line-height: 14px; font-size: 14px;" type="text" name="phone"
                                                  value="{{$phone}}"></p>
                                        @can('user.update.roles', [$user, $roleIds])
                                            <p>
                                                <select style="height: 120px;" id="roles" name="role_ids[]"
                                                        class="form-control" multiple></select>
                                            </p>
                                        @endcan
                                        @can('user.update.children', [$user, array_column($allPossibleChildren, 'id')])
                                            <p>
                                                @if(isset($allPossibleChildren))
                                                    @php
                                                        $children = isset($children) ? $children : [];
                                                    @endphp
                                                    <select style="height: 120px;" id="roles" name="child_ids[]"
                                                            class="form-control" multiple>
                                                        @foreach($allPossibleChildren as $child)
                                                            <option value="{{$child['id']}}"
                                                                    @foreach($children as $selectedChild)
                                                                    @if($selectedChild['id'] === $child['id'])
                                                                    selected
                                                                    @endif
                                                                    @endforeach
                                                            >{{$child['name']}}({{$child['email']}})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </p>
                                        @endcan
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="map-wrap" style="width: 400px;">
                                        <div id="mapid" style="height: 400px; width: 600px;">
                                            <div id="marker" style="z-index: 500;"></div>
                                        </div>

                                    </div>
                                    <input type="hidden" name="latitude" value="{{$latitude}}">
                                    <input type="hidden" name="longitude" value="{{$longitude}}">
                                </div>
                                <div class="row">
                                    <button type="submit" class="pull-right">Update</button>
                                </div>
                            </form>
                        </div>
                        @if($id === $authUser['id'])
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
                        @endif
                        @if(false)
                            <div role="tabpanel" id="find_offers" class="tab-pane">
                                <h4 class="title">Find best offers in best places:</h4>
                                <form action="{{route('places.index')}}" target="_top">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="category">Choose category:</label>
                                        <div class="select">
                                            <select id="place-category" class="form-control"
                                                    name="category_ids[]"></select>
                                            @foreach($errors->get('category_ids') as $message)
                                                <p class="text-danger">
                                                    {{$message}}
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="latitude">Set latitude:</label>
                                        <input type="text" class="form-control" name="latitude" placeholder="40.7142540"
                                               value=""><br>
                                        @foreach($errors->get('latitude') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="latitude">Set longitude</label>
                                        <input type="text" class="form-control" name="longitude"
                                               placeholder="-74.0054797"
                                               value=""><br>
                                        @foreach($errors->get('longitude') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="latitude">Set radius (in meters):</label>
                                        <input type="text" class="form-control" name="radius" placeholder="1000"
                                               value=""><br>
                                        @foreach($errors->get('radius') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                        @endforeach
                                    </div>
                                    <input class="btn btn-rose btn-wd btn-md" type="submit">
                                </form>
                            </div>
                        @endif
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

    <script type="text/javascript">
        @can('user.update.roles', [$user, $roleIds])
        function loadRoles() {
            let xmlhttp = new XMLHttpRequest();
            let currentRoles = {!! json_encode(array_column($roles, 'id')) !!};

            xmlhttp.onreadystatechange = function() {
                if ( xmlhttp.readyState === XMLHttpRequest.DONE ) {
                    if ( xmlhttp.status === 200 ) {
                        let sel = document.getElementById( "roles" );
                        sel.innerHTML = xmlhttp.responseText;
                        for ( let rolesIndex = 0; rolesIndex < sel.options.length; rolesIndex++ ) {
                            let option = sel.options[rolesIndex];
                            if ( currentRoles.indexOf( option.value ) != -1 ) {
                                option.selected = true;
                                console.log( option.value );
                            }
                        }
                    } else if ( xmlhttp.status === 400 ) {
                        alert( 'There was an error 400' );
                    } else {
                        alert( xmlhttp.status + ' was returned' );
                    }
                }
            };

            xmlhttp.open( "GET", "{{route('roles')}}", true );
            xmlhttp.send();
        }

        loadRoles();
        @endcan

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

                    L.tileLayer( '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
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
