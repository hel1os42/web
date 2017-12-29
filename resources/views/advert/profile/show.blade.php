@extends('advert.layout')

@section('title', 'Profile')
@php
    $user = \App\Models\User::query()->find($id);
@endphp
@section('content')
    <script type="text/javascript">
        function loadCategory(){
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === XMLHttpRequest.DONE) {
                    if (xmlhttp.status === 200) {
                        let sel = document.getElementById("place-category");
                        sel.innerHTML = xmlhttp.responseText;
                    }
                    else if (xmlhttp.status === 400) {
                        alert('There was an error 400');
                    }
                    else {
                        alert( xmlhttp.status + ' was returned' );
                    }
                }
            };

            xmlhttp.open("GET", "{{route('categories')}}", true);
            xmlhttp.send();
        }
        @can('users.update.roles', $user)
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

        loadCategory();

    </script>

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
						        <li class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true">Profile info</a>
							    </li>
                                <li><a href="#edit" aria-controls="profile" role="tab" data-toggle="tab"
                                       aria-expanded="true">Edit profile</a>
                                <li class="">
                                    <a href="#update_photo" aria-controls="update_photo" role="tab" data-toggle="tab" aria-expanded="false">
                                        Update profile photo
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#update_place_logo" aria-controls="update_place_logo" role="tab" data-toggle="tab" aria-expanded="false">
                                        Update place logo
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#update_place_cover" aria-controls="update_place_cover" role="tab" data-toggle="tab" aria-expanded="false">
                                        Update place cover
                                    </a>
                                </li>
						    </ul>
					    </div>
					</div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <div class="row">
                                <div class="col-sm-6 p-5">
						            <p><strong>Name</strong></p>
						            <p><strong>Id</strong></p>
						            <p><strong>Email</strong></p>
						            <p><strong>Invite link</strong></p>
						        </div>
                                <div class="col-sm-6 p-10 p-5">
                                    <p>{{$name}}</p>
                                    <p>{{$id}}</p>
                                    <p>{{$email}}</p>
                                    <p>{{route('registerForm', $invite_code)}}</p>
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
                                        <p><strong>Latitude</strong></p>
                                        <p><strong>Longitude</strong></p>
                                    </div>
                                    <div class="col-sm-6 p-10 p-5">
                                        <p style="line-height: 14px; font-size: 14px;">{{$id}}</p>
                                        <p><input style="line-height: 14px; font-size: 14px;" type="text" name="name"
                                                  value="{{$name}}"></p>
                                        <p><input style="line-height: 14px; font-size: 14px;" type="text" name="email"
                                                  value="{{$email}}"></p>
                                        <p><input style="line-height: 14px; font-size: 14px;" type="text" name="phone"
                                                  value="{{$phone}}"></p>
                                        <p><input style="line-height: 14px; font-size: 14px;" type="text"
                                                  name="latitude" value="{{$latitude}}"></p>
                                        <p><input style="line-height: 14px; font-size: 14px;" type="text"
                                                  name="longitude" value="{{$longitude}}"></p>
                                    </div>
                                    <button type="submit">Update</button>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" id="update_photo" class="tab-pane">
                            <form method="POST" action="{{route('profile.picture.store')}}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <h4 class="title">Update your avatar</h4>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4">
                                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                            <div class="fileinput fileinput-new text-center" data-provides="fileinput">
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
                        <div role="tabpanel" id="update_place_logo" class="tab-pane">
                            @include('partials.place-picture-filepicker')
                        </div>
                        <div role="tabpanel" id="update_place_cover" class="tab-pane">
                            @include('partials.place-cover-filepicker')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
