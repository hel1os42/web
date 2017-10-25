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
                                <li class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true">Edit profile</a>
                                </li>
                                <li class=""><a href="#update_photo" aria-controls="update_photo" role="tab" data-toggle="tab" aria-expanded="false">Update photo</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <form action="{{route('users.update', $id)}}">
                                <input name="_method" type="hidden" value="PUT">
                                <div class="row">
                                    <div class="col-sm-6 p-5">
                                        <p><strong>Id</strong></p>
                                        <p><strong>Name</strong></p>
                                        <p><strong>Email</strong></p>
                                        <p><strong>Phone</strong></p>
                                        <p><strong>Latitude</strong></p>
                                        <p><strong>Longitude</strong></p>
                                        <p><strong>Roles</strong></p>
                                    </div>
                                    <div class="col-sm-6 p-10 p-5">
                                        <p>{{$id}}</p>
                                        <p><input type="text" name="name" value="{{$name}}"></p>
                                        <p><input type="text" name="email" value="{{$email}}"></p>
                                        <p><input type="text" name="phone" value="{{$phone}}"></p>
                                        <p><input type="text" name="latitude" value="{{$latitude}}"></p>
                                        <p><input type="text" name="longitude" value="{{$longitude}}"></p>
                                        <p>
                                            <select name="role_ids" multiple>
                                                @foreach($roles as $role)
                                                    <option value="{{$role['name']}}" selected>{{$role['name']}}</option>
                                                @endforeach
                                                //
                                            </select>
                                        </p>
                                    </div>
                                    <input type="submit" value="Update">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop