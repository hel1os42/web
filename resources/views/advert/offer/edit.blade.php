@extends('layouts.master')

@section('title', 'Create offer')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <h1>Edit offer</h1>

                <form action="{{route('advert.offers.update', $id)}}" method="PATCH" class="nau-form" id="createOfferForm" target="_top">

                    {{ csrf_field() }}

                    <p class="title">Name and Description</p>

                    <div class="control-box">
                        <p class="control-text">
                            <label>
                                <span class="input-label">Offer name*</span>
                                <input name="label" value="{{ $label }}" class="formData">
                            </label>
                        </p>
                        <p class="hint">Please, enter the Offer name.</p>
                    </div>

                    <div class="control-box">
                        <p class="control-text">
                            <label>
                                <span class="input-label">Offer description</span>
                                <textarea name="description" class="nullableFormData">{{ $description }}</textarea>
                            </label>
                        </p>
                        <p class="hint">Please, enter the Offer description.</p>
                    </div>

                    <p class="title">Category &amp; Type</p>

                    <div class="control-box">
                        <p class="control-select valid-not-empty">
                            <label>
                                <span class="input-label">Offer category</span>
                                <select id="offer_category" name="category_id" class="formData" value="{{ $category_id }}"></select>
                            </label>
                        </p>
                        <p class="hint">Please, select the category.</p>
                    </div>
                    {{--<div id="tab_step2" class="tab-pane fade">--}}

                    <div class="control-box">
                        <p class="title">Working dates</p>
                        <p class="row control-datetime valid-dates">
                            <label class="col-xs-6"><span class="input-label">from*</span> <input name="start_date" readonly class="js-datepicker" placeholder="Select date" value="{{ $start_date->format('Y-m-d') }}"></label>
                            <label class="col-xs-6"><span class="input-label">to</span> <input name="finish_date" readonly class="js-datepicker" placeholder="Select date" value="{{ $finish_date->format('Y-m-d') }}"></label>
                        </p>
                        <p class="hint">Please, select the valid range of dates.</p>
                    </div>

                    <ul class="nav nav-tabs small">
                        <li><a data-toggle="tab" href="#tab_wdt1">Simple</a></li>
                        <li class="active"><a data-toggle="tab" href="#tab_wdt2">Detailed</a></li>
                    </ul>
                    @php
                        $workingDays = [];
                        foreach ($timeframes as $timeframe){
                            foreach ($timeframe['days'] as $day){
                                $workingDays[$day] = [
                                    'from' => substr($timeframe['from'], 0, 5),
                                    'to' => substr($timeframe['to'], 0, 5),
                                ];
                            }
                        }
                    @endphp
                    <div class="tab-content">
                        <div id="tab_wdt1" class="tab-pane fade">
                            <p class="title">Working days</p>
                            <p class="control-check-left"><input name="____wd_working_days" type="checkbox" id="check_wd8" checked><label for="check_wd8"><span class="input-label">Working Days</span></label></p>
                            <p class="control-check-left"><input name="____wd_weekend" type="checkbox" id="check_wd9"><label for="check_wd9"><span class="input-label">Weekend</span></label></p>
                            <p class="title">Working time</p>
                            <p class="row control-datetime">
                                <label class="col-xs-6"><span class="input-label">from</span> <input name="start_time" readonly class="js-timepicker" value="" id="time_wd8f" placeholder="__:__"></label>
                                <label class="col-xs-6"><span class="input-label">to</span> <input name="finish_time" readonly class="js-timepicker" value="" id="time_wd8t" placeholder="__:__"></label>
                            </p>
                        </div>

                        <div id="tab_wdt2" class="tab-pane fade in active" data-workingdays="{{ json_encode($workingDays) }}">
                            <p class="title">Working days &amp; time <small>You can set working time more flexibility</small></p>
                            <p class="row">
                                <span class="col-xs-2">Work</span>
                                <span class="col-xs-2">Day</span>
                                <span class="col-xs-4">From</span>
                                <span class="col-xs-4">To</span>
                            </p>
                            <p class="row day-info">
                                <span class="col-xs-2">
                                    <span class="control-check-left"><input name="____wd_mon" type="checkbox" id="check_wd1" data-relation="check_wd8" data-weekday="mo"><label for="check_wd1">&nbsp;</label></span>
                                </span>
                                                <strong class="col-xs-2">Mon</strong>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____start_time_mon" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="mo" placeholder="__:__"></label>
                                </span>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____finish_time_mon" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="mo" placeholder="__:__"></label>
                                </span>
                                            </p>
                                            <p class="row day-info">
                                <span class="col-xs-2">
                                    <span class="control-check-left"><input name="____wd_tue" type="checkbox" id="check_wd2" data-relation="check_wd8" data-weekday="tu"><label for="check_wd2">&nbsp;</label></span>
                                </span>
                                                <strong class="col-xs-2">Tue</strong>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____start_time_tue" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="tu" placeholder="__:__"></label>
                                </span>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____finish_time_tue" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="tu" placeholder="__:__"></label>
                                </span>
                                            </p>
                                            <p class="row day-info">
                                <span class="col-xs-2">
                                    <span class="control-check-left"><input name="____wd_wed" type="checkbox" id="check_wd3" data-relation="check_wd8" data-weekday="we"><label for="check_wd3">&nbsp;</label></span>
                                </span>
                                                <strong class="col-xs-2">Wed</strong>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____start_time_wed" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="we" placeholder="__:__"></label>
                                </span>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____finish_time_wed" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="we" placeholder="__:__"></label>
                                </span>
                                            </p>
                                            <p class="row day-info">
                                <span class="col-xs-2">
                                    <span class="control-check-left"><input name="____wd_thu" type="checkbox" id="check_wd4" data-relation="check_wd8" data-weekday="th" ><label for="check_wd4">&nbsp;</label></span>
                                </span>
                                                <strong class="col-xs-2">Thu</strong>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____start_time_thu" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="th" placeholder="__:__"></label>
                                </span>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____finish_time_thu" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="th" placeholder="__:__"></label>
                                </span>
                                            </p>
                                            <p class="row day-info">
                                <span class="col-xs-2">
                                    <span class="control-check-left"><input name="____wd_fri" type="checkbox" id="check_wd5" data-relation="check_wd8" data-weekday="fr" ><label for="check_wd5">&nbsp;</label></span>
                                </span>
                                                <strong class="col-xs-2">Fri</strong>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____start_time_fri" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="fr" placeholder="__:__"></label>
                                </span>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____finish_time_fri" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="fr" placeholder="__:__"></label>
                                </span>
                                            </p>
                                            <p class="row day-info">
                                <span class="col-xs-2">
                                    <span class="control-check-left"><input name="timeframes['days'][]" value="sa" type="checkbox" id="check_wd6" data-weekday="sa" data-relation="check_wd9"><label for="check_wd6">&nbsp;</label></span>
                                </span>
                                                <strong class="col-xs-2">Sat</strong>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____start_time_sat" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="sa" placeholder="__:__"></label>
                                </span>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____finish_time_sat" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="sa" placeholder="__:__"></label>
                                </span>
                                            </p>
                                            <p class="row day-info">
                                <span class="col-xs-2">
                                    <span class="control-check-left"><input name="timeframes['days'][]" value="su" type="checkbox" id="check_wd7" data-weekday="su" data-relation="check_wd9"><label for="check_wd7">&nbsp;</label></span>
                                </span>
                                                <strong class="col-xs-2">Sun</strong>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____start_time_sun" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="su" placeholder="__:__"></label>
                                </span>
                                                <span class="col-xs-4 control-datetime">
                                    <label><input name="____finish_time_sun" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="su" placeholder="__:__"></label>
                                </span>
                            </p>
                            <p class="hint working-dt-hint">Please, select the days and set the time.</p>
                        </div>
                    </div>

                    <p class="title" style="margin-top: 110px;">Working area</p>

                    <div class="control-box">
                        <p><strong>Setting map radius</strong></p>

                        <div class="map-wrap" >
                            <div class="leaflet-map" id="mapid">
                            </div>
                            <div id="marker"></div>
                        </div>

                        <input type="hidden" name="latitude" value="{{ $latitude }}" class="mapFields nullableFormData">
                        <input type="hidden" name="longitude" value="{{ $longitude }}" class="mapFields nullableFormData">
                        <input type="hidden" name="radius" value="{{ $radius }}" class="mapFields nullableFormData">

                    </div>

                    <div class="control-box">
                        <p class="control-text">
                            <label>
                                <span class="input-label">Country</span>
                                <input name="country" value="{{ $country }}" class="nullableFormData">
                            </label>
                        </p>
                    </div>

                    <div class="control-box">
                        <p class="control-text">
                            <label>
                                <span class="input-label">City</span>
                                <input name="city" value="{{ $city }}" class="nullableFormData">
                            </label>
                        </p>
                    </div>

                    <p class="title">Max redemption total <small>Zero is infinity</small></p>
                    <p class="control-range max-redemption"><span class="input-label">Overral</span> <em role="button" class="more">+</em><label><input name="max_count" data-min="0" data-max="1000" data-default="0" value="{{ ($max_count === null) ? 0 : $max_count }}" class="js-numeric nullableLimit"></label><em role="button" class="less">-</em></p>
                    <p class="control-range max-redemption"><span class="input-label">Daily</span> <em role="button" class="more">+</em><label><input name="max_per_day" data-min="0" data-max="1000" data-default="0" value="{{ ($max_per_day === null) ? 0 : $max_per_day }}" class="js-numeric nullableLimit"></label><em role="button" class="less">-</em></p>

                    <p class="title">Max redemption per user <small>Zero is infinity</small></p>
                    <p class="control-range max-redemption"><span class="input-label">Overral</span> <em role="button" class="more">+</em><label><input name="max_for_user" data-min="0" data-max="1000" data-default="0" value="{{ ($max_for_user === null) ? 0 : $max_for_user }}" class="js-numeric nullableLimit"></label><em role="button" class="less">-</em></p>
                    <p class="control-range max-redemption"><span class="input-label">Daily</span> <em role="button" class="more">+</em><label><input name="max_for_user_per_day" data-min="0" data-max="1000" data-default="0" value="{{ ($max_for_user_per_day === null) ? 0 : $max_for_user_per_day }}" class="js-numeric nullableLimit"></label><em role="button" class="less">-</em></p>
                    <p class="control-range max-redemption"><span class="input-label">Weekly</span> <em role="button" class="more">+</em><label><input name="max_for_user_per_week" data-min="0" data-max="1000" data-default="0" value="{{ ($max_for_user_per_week === null) ? 0 : $max_for_user_per_week }}" class="js-numeric nullableLimit"></label><em role="button" class="less">-</em></p>
                    <p class="control-range max-redemption"><span class="input-label">Monthly</span> <em role="button" class="more">+</em><label><input name="max_for_user_per_month" data-min="0" data-max="1000" data-default="0" value="{{ ($max_for_user_per_month === null) ? 0 : $max_for_user_per_month }}" class="js-numeric nullableLimit"></label><em role="button" class="less">-</em></p>

                    <p class="title">Other limits</p>
                    <p class="control-range"><span class="input-label">Minimal user level*</span> <em role="button" class="more">+</em><label><input name="user_level_min" data-min="1" data-max="99" data-default="1" value="{{ $user_level_min }}" class="js-numeric formData"></label><em role="button" class="less">-</em></p>

                    <p class="title">Reward options</p>
                    <p class="control-range"><span class="input-label">Reward for redemption*</span> <em role="button" class="more">+</em><label><input name="reward" data-min="1" data-max="999999" data-default="1" value="{{ $reward }}" class="js-numeric formData"></label><em role="button" class="less">-</em></p>
                    <p class="control-range"><span class="input-label">Token reservation*</span> <em role="button" class="more">+</em><label><input name="reserved" data-min="10" data-max="999999" data-default="10" value="{{ $reserved }}" class="js-numeric formData"></label><em role="button" class="less">-</em></p>

                    <p class="tokens-total"><strong>{{ $authUser['accounts']['NAU']['balance'] }}</strong> <span>You have tokens on your account</span></p>

                    <p class="step-footer">
                        {{--<a href="#tab_step3" data-toggle="tab" class="tab-nav btn-nau pull-left">&lt; prev step</a>--}}
                        <input type="submit" class="btn-nau pull-right" value="Save">
                    </p>

                </form>

                <div id="formOverlay">
                    <div id="formInformationModal">
                        <p class="msg">Sending...</p>
                        <img src="{{ asset('img/loading.gif') }}" alt="loading..." class="loading">
                    </div>
                </div>

                @push('scripts')
                    <script type="text/javascript">
                        /* offer_category */
                        let xhr = new XMLHttpRequest();

                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                if (xhr.status === 200) {
                                    document.getElementById("offer_category").innerHTML = xhr.responseText;
                                }
                                else if (xhr.status === 400) {
                                    console.log('Get categories: there was an error 400');
                                }
                                else {
                                    console.log('Get categories: something else other than 200 was returned');
                                }
                            }
                        };

                        xhr.open("GET", "{{ route('categories') }}", true);
                        xhr.send();
                    </script>

                @endpush

            </div>
        </div>
    </div>


    @push('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/datetimepicker.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('js/partials/create-offer-validator.js') }}"></script>
        <script src="{{ asset('js/partials/create-offer-sender.js') }}"></script>
        <script src="{{ asset('js/partials/datetimepicker.js') }}"></script>
        <script src="{{ asset('js/partials/control-range.js') }}"></script>
        <script src="{{ asset('js/partials/image-uploader.js') }}"></script>
        <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
        <script>

            /* dateTime picker init */
            dateTimePickerInit();

            /* control range init */
            controlRange('.js-numeric');
            maxRedemptionInfinity('.max-redemption input');

            /* image uploader init */
            imageUploader('.js-imgupload');

            /* tab-step-control and tab-validator */
            tabStepControlInit();

            /* "Working Days & Time" checking days */
            checkingDays();

            /* synchronization of wokr time */
            wokrTimeSynchronization();

            /* offer type = discount */
            offerTypeController();

            function dateTimePickerInit(){
                let $startDate = $('[name="start_date"]'),
                    $finishDate = $('[name="finish_date"]');
                $startDate.on('focus click', function(){
                    datePicker($(this), new Date());
                });
                $finishDate.on('focus click', function(){
                    if (!$startDate.val()) $startDate.focus();
                    else datePicker($(this), new Date($startDate.val()));
                });
                $('.js-timepicker').on('focus click', function(){
                    timePicker($(this));
                });
            }

            function tabStepControlInit() {
                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    if ($(e.target).hasClass('tab-nav')) {
                        let idActiveTab = $(e.target).attr('href');
                        $('.tab-step-control > li.active').removeClass('active');
                        $('.tab-step-control [href="' + idActiveTab + '"]').parents('.tab-step-control > li').addClass('active');
                    }
                    $('.tab-step-control li.active').prevAll().find('a').each(function () {
                        tabValidator[$(this).attr('href')]();
                    });
                });
            }

            function checkingDays(){
                $('.day-info').each(function(){
                    let $p = $(this);
                    let $cb = $p.find('[type="checkbox"]');
                    if (!$cb.is(':checked')) $p.addClass('passive');
                    $cb.on('change', function(){
                        $p.toggleClass('passive', !$(this).is(':checked'));
                        let $inputs = $p.find('.js-timepicker');
                        let passive = $p.hasClass('passive');
                        $inputs.eq(0).val(passive ? '00:00' : '');
                        $inputs.eq(1).val(passive ? '23:59' : '');
                    }).trigger('change');
                });
            }

            function wokrTimeSynchronization(){
                /* да, сложно и запутанно */
                $('#check_wd8, #check_wd9').on('change', function(){
                    let state = this.checked;
                    $('[data-relation="' + $(this).attr('id') + '"]').prop('checked', state).trigger('change');
                });
                $('#time_wd8f, #time_wd8t').on('change', function(){
                    let val = $(this).val();
                    if ($('#check_wd8').is(':checked')) {
                        $('[data-relation="check_wd8"]').parents('p').find('[data-relation="' + $(this).attr('id') + '"]');
                    }
                    if ($('#check_wd9').is(':checked')) {
                        $('[data-relation="check_wd9"]').parents('p').find('[data-relation="' + $(this).attr('id') + '"]');
                    }
                });
                /*$('[data-relation^="time_wd8f_"]').each(function(){
                    $(this).on('change', function(){ $('#time_wd8f').val($(this).val()); });
                });
                $('[data-relation^="time_wd8t_"]').each(function(){
                    $(this).on('change', function(){ $('#time_wd8t').val($(this).val()); });
                });*/
            }

            function offerTypeController(){
                let $rads = $('.offer-type-box').find('[type="radio"]');
                $rads.on('change', function(){
                    $rads.each(function(){
                        $(this).parents('p').next('.sub-radio')['slide' + ($(this).is(':checked') ? 'Down' : 'Up')]();
                    });
                }).trigger('change');
            }

            $( document ).ready( function() {
                let GPS = {};
                let defaultZoom = 1;
                if ( navigator.geolocation ) {
                    navigator.geolocation.getCurrentPosition( getGPS, defaultGPS );
                }

                function getGPS( pos ) {
                    GPS = {
                        lat: pos.coords.latitude,
                        lng: pos.coords.longitude
                    };
                    defaultZoom = 13;
                    mapInitialize( GPS, defaultZoom );
                }
                function defaultGPS() {
                    GPS = {
                        lat: +$('[name="latitude"]').val(),
                        lng: +$('[name="longitude"]').val()
                    };
                    mapInitialize( GPS, defaultZoom );
                }


                function mapInitialize( GPS, defaultZoom ) {
                    lat =$('[name="latitude"]').val();
                    lng = $('[name="longitude"]').val();
                    if (lat !== 0 && lng !== 0) {
                        GPS = {
                            lat: +lat,
                            lng: +lng
                        };
                    };
                    let map = L.map( 'mapid', {
                        center: GPS,
                        zoom:   defaultZoom// 13
                    } );

                    L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom:       19,
                        minZoom:       1,
                        maxNativeZoom: 18,
                        attribution:   '© OpenStreetMap',
                    } ).addTo( map );


                    function fillMapFields(map){
                        let radiusPx = 190;
                        $('[name="latitude"]').val(map.getCenter().lat);
                        $('[name="longitude"]').val(map.getCenter().lng);
                        $('[name="radius"]').val(Math.round(getRadius(radiusPx, map)));
                        function getRadius(radiusPx, map) {
                            return 40075016.686 * Math.abs(Math.cos(map.getCenter().lat / 180 * Math.PI)) / Math.pow(2, map.getZoom()+8) * radiusPx;
                        }

                        function getZoom(latitude, radius) {
                            let zoom = this.round(Math.log2(40075016.686 * 75 * Math.abs(Math.cos(latitude / 180 * Math.PI)) / radius) - 8, 0.25);
                            return zoom;
                        }

                        function round(value, step) {
                            step || (step = 1.0);
                            let inv = 1.0 / step;
                            return Math.round(value * inv) / inv;
                        }
                    }

                    $(map).on('zoomend, moveend', function(){
                        fillMapFields(this);
                    });

                    fillMapFields(map);
                    fillTimeframes(map);
                    handleForm(map);
                }
            } );

            function fillTimeframes(map){
                function getTZ(map, callback){
                    let googleApiKey = 'AIzaSyBDIVqRKhG9ABriA2AhOKe238NZu3cul9Y';
                    let url = 'https://maps.googleapis.com/maps/api/timezone/json?';
                    let timestamp = Math.round(new Date().valueOf() / 1000);
                    let lat = map.getCenter().lat;
                    let lng = map.getCenter().lng;
                    let requestUrl = url + `location=${lat}, ${lng}&timestamp=${timestamp}&key=${googleApiKey}`;
                    return httpGetAsync(map, requestUrl, callback);

                    function httpGetAsync(map, theUrl, callback)
                    {
                        let xmlHttp = new XMLHttpRequest();
                        xmlHttp.onreadystatechange = function() {
                            if (4 == xmlHttp.readyState && 200 == xmlHttp.status){
                                let response = JSON.parse(xmlHttp.responseText);
                                function convertRawOffset(raw){
                                    let absRawInHr = Math.abs(raw / 3600);
                                    let converted = (absRawInHr <= 9) ? ('0'+absRawInHr+'00') : (absRawInHr+'00');
                                    return (raw < 0) ? ('-' + converted) : ('+' + converted);
                                }
                                let tz = convertRawOffset(response.rawOffset);
                                callback(map, tz);
                            }
                        }
                        xmlHttp.open("GET", theUrl, true);
                        xmlHttp.send(null);
                    }
                }

                getTZ(map, fillTimeframesCallback);
                function fillTimeframesCallback(map, tz){
                    let absRawInHr = tz.substr(-4,2);
                    let tzInHr = ('-' === tz.substr(0,1)) ? -1*(+absRawInHr) : (+absRawInHr);

                    let oldweekdays = $('#tab_wdt2').data('workingdays');
                    $('[data-relation="check_wd8"]').each(function(){
                        let currentWeekday = $(this).data('weekday');
                        if(currentWeekday in oldweekdays){
                            $('[data-relation="check_wd8"][data-weekday="' + currentWeekday + '"]').attr('checked', 'checked');

                            $('[data-relation="time_wd8f"][data-weekday="' + currentWeekday + '"]').val(addTz(oldweekdays[currentWeekday].from, tzInHr));
                            $('[data-relation="time_wd8t"][data-weekday="' + currentWeekday + '"]').val(addTz(oldweekdays[currentWeekday].to, tzInHr));
                        } else {
                            $('[data-relation="time_wd8f"][data-weekday="' + currentWeekday + '"]').val("");
                            $('[data-relation="time_wd8t"][data-weekday="' + currentWeekday + '"]').val("");
                        }
                    });
                    function addTz(timeStr, tzNum)
                    {
                        let timeHrsNum = +timeStr.substr(0,2);
                        return (timeHrsNum+tzNum) + timeStr.substr(2);
                    }
                }
            }

            function handleForm(map){

                $('#createOfferForm').on('submit', function (e){
                    e.preventDefault();

                    function getTZ(map, callback){
                        let googleApiKey = 'AIzaSyBDIVqRKhG9ABriA2AhOKe238NZu3cul9Y';
                        let url = 'https://maps.googleapis.com/maps/api/timezone/json?';
                        let timestamp = Math.round(new Date().valueOf() / 1000);
                        let lat = map.getCenter().lat;
                        let lng = map.getCenter().lng;
                        let requestUrl = url + `location=${lat}, ${lng}&timestamp=${timestamp}&key=${googleApiKey}`;
                        return httpGetAsync(map, requestUrl, callback);

                        function httpGetAsync(map, theUrl, callback)
                        {
                            let xmlHttp = new XMLHttpRequest();
                            xmlHttp.onreadystatechange = function() {
                                if (4 == xmlHttp.readyState && 200 == xmlHttp.status){
                                    let response = JSON.parse(xmlHttp.responseText);
                                    function convertRawOffset(raw){
                                        let absRawInHr = Math.abs(raw / 3600);
                                        let converted = (absRawInHr <= 9) ? ('0'+absRawInHr+'00') : (absRawInHr+'00');
                                        return (raw < 0) ? ('-' + converted) : ('+' + converted);
                                    }
                                    let tz = convertRawOffset(response.rawOffset);
                                    callback(map, tz);
                                }
                            }
                            xmlHttp.open("GET", theUrl, true);
                            xmlHttp.send(null);
                        }
                    }

                    getTZ(map, getFormData);

                    function getFormData(map, tz){
                        let timeframes = [];
                        if (true === $('#tab_wdt1').hasClass('active')){
                            let weekdays = {
                                "all" : ["mo", "tu", "we", "th", "fr", "su", "sa"],
                                "working" : ["mo", "tu", "we", "th", "fr"],
                                "weekend" : ["su", "sa"]
                            };
                            let workingDaysState = $('input[name="____wd_working_days"]').is(':checked');
                            let weekendDaysState = $('input[name="____wd_weekend"]').is(':checked');
                            let startTime = $('input[name="start_time"]').val();
                            let finishTime = $('input[name="finish_time"]').val();
                            if(true === workingDaysState && true === weekendDaysState) {
                                timeframes.push(compactTimeframe(weekdays.all, startTime, finishTime, tz));
                            } else if (true === workingDaysState) {
                                timeframes.push(compactTimeframe(weekdays.working, startTime, finishTime, tz));
                            } else if (true === weekendDaysState){
                                timeframes.push(compactTimeframe(weekdays.weekend, startTime, finishTime, tz));
                            }
                        } else if (true === $('#tab_wdt2').hasClass('active')){
                            let weekdays = {};
                            $('[data-relation="check_wd8"]:checked, [data-relation="check_wd9"]:checked').each(function(){
                                currentWeekday = $(this).data('weekday');
                                timeFrom = $('[data-relation="time_wd8f"][data-weekday="' + currentWeekday + '"]').val();
                                timeTo = $('[data-relation="time_wd8t"][data-weekday="' + currentWeekday + '"]').val();
                                key = timeFrom + '-' + timeTo;
                                weekdays[key] = (Array.isArray(weekdays[key])) ? weekdays[key].concat(currentWeekday) : new Array(currentWeekday);
                            });
                            $.each(weekdays, function(times, days){
                                timesArray = times.split('-');
                                timeframes.push(compactTimeframe(days, timesArray[0], timesArray[1], tz));
                            });
                        }

                        let formData = $('.formData').serializeArray();
                        $('.nullableLimit').each(function(){
                            formData.push({
                                "name" : $(this).prop('name'),
                                "value" : ($(this).val() === '0') ? null : $(this).val()
                            });
                        });
                        $('.nullableFormData').each(function(){
                            let value = (
                                            $(this).val() === '0'
                                            || $(this).val() === undefined
                                            || $(this).val() === ''
                                        ) ? null : $(this).val();
                            formData.push({
                                "name" : $(this).prop('name'),
                                "value" : value
                            });
                        });
                        let startDateObj = new Date($('[name="start_date"]').val());
                        formData.push({
                            "name" : "start_date",
                            "value" : prepareDate(startDateObj, tz)
                        });
                        let finishDateVal = $('[name="finish_date"]').val();
                        formData.push({
                            "name" : "finish_date",
                            "value" : ('' == finishDateVal) ? null : prepareDate(new Date(finishDateVal), tz)
                        });

                        function prepareDate(date, tz) {
                            return date.getFullYear()+"-"+prependWithZero(date.getMonth()+1)+"-"+prependWithZero(date.getDate())+' 00:00:00.000000'+ tz;
                            function prependWithZero(number) {
                                return ("0" + number).slice(-2);
                            }
                        }

                        $.each(timeframes, function(key, timeframe){
                            formData.push({
                                "name" : "timeframes[" + key + "][from]",
                                "value" : timeframe.from
                            });
                            formData.push({
                                "name" : "timeframes[" + key + "][to]",
                                "value" : timeframe.to
                            });
                            $.each(timeframe.days, function(dayKey, day){
                                formData.push({
                                    "name" : "timeframes[" + key + "][days][" + dayKey + "]",
                                    "value" : day
                                });
                            }, key);
                        });

                        formData.push({
                            "name" : "_token",
                            "value" : $('[name="_token"]').val()
                        });
                        console.log(formData);
                        $.ajax({
                            type:  $('#createOfferForm').attr('method'),
                            url: $('#createOfferForm').attr('action'),
                            headers: {
                                'Accept':'application/json',
                            },
                            data: formData,
                            success: function(data, textStatus, xhr){
                                if (202 == xhr.status){
                                    return window.location.replace("{{ route('advert.offers.index') }}");
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
                    };

                    function compactTimeframe(days, from, to, timezoneStr){
                        return {
                            "from": from + ':00.000000' + timezoneStr,
                            "to": to + ':00.000000' + timezoneStr,
                            "days": days
                        };
                    }
                });
            }

        </script>
    @endpush

@stop
