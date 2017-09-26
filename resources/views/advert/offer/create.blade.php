@extends('layouts.master')

@section('title', 'Create offer')

@section('content')

    <section class="offer">
        <div class="offer-form">
            <form action="{{route('advert.offers.store')}}" method="post" target="_top">
                {{ csrf_field() }}

                <div>
                    <label for='label'>label</label>
                    <span class="error has-error"></span>
                    <input id="label" name="label" placeholder="label" value="{{old('label')}}">
                </div>

                <div>
                    <label for='description'>description</label>
                    <span class="error has-error"></span>
                    <input id="description" name="description" placeholder="description" value="{{old('description')}}">
                </div>

                <div>
                    <label for='reward'>reward</label>
                    <span class="error has-error"></span>
                    <input id="reward" name="reward" type="number" placeholder="reward" value="{{old('reward')}}">
                </div>

                <div>
                    <label for='start_date'>start date</label>
                    <span class="error has-error"></span>
                    <input id="start_date" name="start_date" placeholder="start_date" value="{{old('start_date')}}">
                </div>

                <div>
                    <label for='finish_date'>finish date</label>
                    <span class="error has-error"></span>
                    <input id="finish_date" name="finish_date" placeholder="finish_date" value="{{old('finish_date')}}">
                </div>

                <div>
                    <label for='start_time'>start time</label>
                    <span class="error has-error"></span>
                    <input id="start_time" name="start_time" type="text" placeholder="start_time" value="{{old('start_time')}}">
                </div>

                <div>
                    <label for='finish_time'>finish time</label>
                    <span class="error has-error"></span>
                    <input id="finish_time" name="finish_time" type="text" placeholder="finish_time" value="{{old('finish_time')}}">
                </div>

                <div>
                    <label for='offer_category'>offer-category</label>
                    <span class="error has-error"></span>
                    <select id="offer_category" name="category_id"></select>
                </div>

                <div>
                    <label for='max_count'>max count</label>
                    <input id="max_count" name="max_count" type="number" max="1000" min="0" placeholder="max_count" value="{{old('max_count')}}">
                    <span class="error has-error"></span>
                </div>

                <div>
                    <label for='max_for_user'>max for user</label>
                    <input id="max_for_user" name="max_for_user" type="number" max="1000" min="0" placeholder="max_for_user" value="{{old('max_for_user')}}">
                    <span class="error has-error"></span>
                </div>

                <div>
                    <label for='max_per_day'>max per day</label>
                    <input id="max_per_day" name="max_per_day" type="number" max="1000" min="0" placeholder="max_per_day" value="{{old('max_per_day')}}">
                    <span class="error has-error"></span>
                </div>

                <div>
                    <label for='max_for_user_per_day'>max for user per day</label>
                    <span class="error has-error"></span>
                    <input id="max_for_user_per_day" name="max_for_user_per_day" type="number" max="1000" min="0" placeholder="max_for_user_per_day"  value="{{old('max_for_user_per_day')}}">
                </div>

                <div>
                    <label for='user_level_min'>user level min</label>
                    <span class="error has-error"></span>
                    <input id="user_level_min" name="user_level_min" type="number" max="1000" min="0" placeholder="user_level_min" value="{{old('user_level_min')}}">
                </div>

                <div>
                    <label for='latitude'>latitude</label>
                    <span class="error has-error"></span>
                    <input id="latitude" name="latitude" placeholder="latitude" value="{{old('latitude')}}">
                </div>

                <div>
                    <label for='longitude'>longitude</label>
                    <span class="error has-error"></span>
                    <input id="longitude" name="longitude" placeholder="longitude" value="{{old('longitude')}}">
                </div>

                <div>
                    <label for='radius'>radius</label>
                    <span class="error has-error"></span>
                    <input id="radius" name="radius" type="number" placeholder="radius" value="{{old('radius')}}">
                </div>

                <div>
                    <label for='country'>country</label>
                    <span class="error has-error"></span>
                    <input id="country" name="country" placeholder="country" value="{{old('country')}}">
                </div>

                <div>
                    <label for='city'>city</label>
                    <span class="error has-error"></span>
                    <input id="city" name="city" type="text" placeholder="city" value="{{old('city')}}">
                </div>

                <div>
                    <label for='address'>address</label>
                    <span class="error has-error"></span>
                    <input id="address" name="address" type="text" placeholder="address">
                </div>

                <input type="submit">
            </form>
        </div>
    </section>

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