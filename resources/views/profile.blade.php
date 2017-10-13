@extends('layouts.master')

@section('title', 'Profile')

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
                        alert('something else other than 200 was returned');
                    }
                }
            };

            xmlhttp.open("GET", "{{route('categories')}}", true);
            xmlhttp.send();
        }

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
							    <li class=""><a href="#links" aria-controls="links" role="tab" data-toggle="tab" aria-expanded="false">Links</a>
							    </li>
							    <li class=""><a href="#update_photo" aria-controls="update_photo" role="tab" data-toggle="tab" aria-expanded="false">Update photo</a>
							    </li>
                                <li class=""><a href="#places" aria-controls="places" role="tab" data-toggle="tab" aria-expanded="false">Find places</a>
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
                                    <p><a href="{{route('registerForm', $invite_code)}}">{{route('registerForm', $invite_code)}}</a></p>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" id="links" class="tab-pane">
                            <br>Links:<br>
                            <a href="{{route('referrals')}}">{{route('referrals')}}</a><br>
                            <a href="{{route('profile.place.show')}}">{{route('profile.place.show')}}</a><br>
                            <a href="{{route('profile.place.offers')}}">{{route('profile.place.offers')}}</a><br>
                            <br>Offers:<br>
                            <a href="{{route('advert.offers.create')}}">{{route('advert.offers.create')}}</a><br>
                            <a href="{{route('advert.offers.index')}}">{{route('advert.offers.index')}}</a><br>
                            <a href="{{route('offers.index')}}">{{route('offers.index')}}</a><br>
                            <br>Operations:<br>
                            <a href="{{ route('transactionList') }}">{{ route('transactionList')}}</a><br>
                            <a href="{{ route('transactionCreate') }}">{{ route('transactionCreate') }}</a><br>
                            <br>Places:<br>
                            <a href="{{ route('places.create') }}">{{ route('places.create') }}</a><br>
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
                        <div role="tabpanel" id="places" class="tab-pane">
                            <h4 class="title">Find best offers in best places:</h4>
                            <form action="{{route('places.index')}}" target="_top">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="category">Choose category:</label>
                                    <div class="select">
                                        <select id="place-category" class="form-control" name="category_ids[]"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="latitude">Set latitude:</label>
                                    <input type="text" class="form-control" name="latitude" placeholder="40.7142540" value=""><br>
                                </div>
                                <div class="form-group">
                                    <label for="latitude">Set longitude</label>
                                    <input type="text" class="form-control" name="longitude" placeholder="-74.0054797" value=""><br>
                                </div>
                                <div class="form-group">
                                    <label for="latitude">Set radius (in meters):</label>
                                    <input type="text" class="form-control" name="radius" placeholder="1000" value=""><br>
                                </div>
                                <input class="btn btn-rose btn-wd btn-md" type="submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

@stop
