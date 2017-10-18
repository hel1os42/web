@extends('layouts.master')

@section('title', 'Create offer')

@section('content')

    <div class="col-md-12">
        <div class="content">
            <h3 class="title">Create advert</h3>
            <div class="card">
                <div class="content">

                    <form class="form-horizontal" action="{{route('advert.offers.store')}}" method="post" target="_top">
                    <div class="row">
                        {{ csrf_field() }}

                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Label</label>
                                <div class="col-sm-10">
                                    <input id="label" class="form-control" name="label" placeholder="label" value="{{old('label')}}">
                                    @foreach($errors->get('label') as $message)
                                        <p class="text-danger">
                                            {{$message}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Description</label>
                                <div class="col-sm-10">
                                    <input id="description" class="form-control" name="description" placeholder="description" value="{{old('description')}}">
                                    @foreach($errors->get('description') as $message)
                                        <p class="text-danger">
                                            {{$message}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Reward</label>
                                <div class="col-sm-10">
                                    <input id="reward" class="form-control" type="number" name="reward" placeholder="reward" value="{{old('reward')}}">
                                    @foreach($errors->get('reward') as $message)
                                        <p class="text-danger">
                                            {{$message}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Start date</label>
                                <div class="col-sm-10">
                                    <input id="start_date" class="form-control" name="start_date" placeholder="start date" value="{{old('start_date')}}">
                                    @foreach($errors->get('start_date') as $message)
                                        <p class="text-danger">
                                            {{$message}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Finish date</label>
                                <div class="col-sm-10">
                                    <input id="finish_date" class="form-control" name="finish_date" placeholder="finish date" value="{{old('finish_date')}}">
                                    @foreach($errors->get('finish_date') as $message)
                                        <p class="text-danger">
                                            {{$message}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Start time</label>
                                <div class="col-sm-10">
                                    <input id="start_time" class="form-control" type="text" name="start_time" placeholder="start time" value="{{old('start_time')}}">
                                    @foreach($errors->get('start_time') as $message)
                                        <p class="text-danger">
                                            {{$message}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Finish time</label>
                                <div class="col-sm-10">
                                    <input id="finish_time" class="form-control" type="text" name="finish_time" placeholder="finish time" value="{{old('finish_time')}}">
                                    @foreach($errors->get('finish_time') as $message)
                                        <p class="text-danger">
                                            {{$message}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Offer category</label>
                                <div class="col-sm-10">
                                    <div class="select">
                                        <select id="offer_category" class="form-control" name="category_id"></select>
                                        @foreach($errors->get('category_id') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Max count</label>
                                <div class="col-sm-10">
                                    <input id="max_count" max="1000" min="0" class="form-control" type="number" name="max_count" placeholder="max count" value="{{old('max_count')}}">
                                    @foreach($errors->get('max_count') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Max for user</label>
                                <div class="col-sm-10">
                                    <input id="max_for_user" max="1000" min="0" class="form-control" type="number" name="max_for_user" placeholder="max for user" value="{{old('max_for_user')}}">
                                    @foreach($errors->get('max_for_user') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Max per day</label>
                                <div class="col-sm-10">
                                    <input id="max_per_day" max="1000" min="0" class="form-control" type="number" name="max_per_day" placeholder="max per day" value="{{old('max_per_day')}}">
                                    @foreach($errors->get('max_per_day') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Max for user per day</label>
                                <div class="col-sm-10">
                                    <input id="max_for_user_per_day" max="1000" min="0" class="form-control" type="number" name="max_for_user_per_day" placeholder="max for user per day" value="{{old('max_for_user_per_day')}}">
                                    @foreach($errors->get('max_for_user_per_day') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">User level min</label>
                                <div class="col-sm-10">
                                    <input id="user_level_min" max="1000" min="0" class="form-control" type="number" name="user_level_min" placeholder="user level min" value="{{old('user_level_min')}}">
                                    @foreach($errors->get('user_level_min') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach

                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Latitude</label>
                                <div class="col-sm-10">
                                    <input id="latitude" class="form-control" name="latitude" placeholder="latitude" value="{{old('latitude')}}">
                                    @foreach($errors->get('latitude') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                         <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Longitude</label>
                                <div class="col-sm-10">
                                    <input id="longitude" class="form-control" name="longitude" placeholder="longitude" value="{{old('longitude')}}">
                                    @foreach($errors->get('longitude') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>                       
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Radius</label>
                                <div class="col-sm-10">
                                    <input id="radius" class="form-control" name="radius" placeholder="radius" value="{{old('radius')}}">
                                    @foreach($errors->get('radius') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Country</label>
                                <div class="col-sm-10">
                                    <input id="country" class="form-control" name="country" placeholder="country" value="{{old('country')}}">
                                    @foreach($errors->get('country') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">City</label>
                                <div class="col-sm-10">
                                    <input id="city" class="form-control" name="city" placeholder="city" value="{{old('city')}}">
                                    @foreach($errors->get('city') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                        <div class="col-md-6"><fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Address</label>
                                <div class="col-sm-10">
                                    <input id="address" class="form-control" name="address" placeholder="address" value="{{old('address')}}">
                                    @foreach($errors->get('address') as $message)
                                            <p class="text-danger">
                                                {{$message}}
                                            </p>
                                    @endforeach
                                </div>
                            </div>
                        </div></fieldset>
                    </div>
                    <input class="btn btn-rose btn-wd btn-md" type="submit">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <section class="map">
            <div id="map"></div>
    </section>


    <script type="text/javascript">
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                if (xmlhttp.status == 200) {
                    document.getElementById("offer_category").innerHTML = xmlhttp.responseText;
                }
                else if (xmlhttp.status == 400) {
                    alert('There was an error 400');
                }
                else {
                    alert('something else other than 200 was returned');
                }
            }
        };

        xmlhttp.open("GET", "{{route('categories')}}", true);
        xmlhttp.send();
    </script>

@stop
