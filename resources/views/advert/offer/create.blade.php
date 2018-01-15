@extends('layouts.master')

@section('title', 'Create offer')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

            <h1>Create offer</h1>

            <form action="{{route('advert.offers.store')}}" method="post" class="nau-form" id="createOfferForm" target="_top">

                {{--<ul class="tab-step-control js-tabs">--}}
                    {{--<li class="active"><a href="#tab_step1" data-toggle="tab"><em>1</em> Main<br>Information</a></li>--}}
                    {{--<li><a href="#tab_step2" data-toggle="tab"><em>2</em> Working Dates<br>&amp; Times</a></li>--}}
                    {{--<li><a href="#tab_step3" data-toggle="tab"><em>3</em> Working<br>Area</a></li>--}}
                    {{--<li><a href="#tab_step4" data-toggle="tab"><em>4</em> Additional<br>Settings</a></li>--}}
                {{--</ul>--}}

                {{--<div class="tab-content tab-step-content">--}}
                    @include('advert.offer.create-step1')
                    @include('advert.offer.create-step2')
                    @include('advert.offer.create-step3')
                    @include('advert.offer.create-step4')
                {{--</div>--}}

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
@endpush

@push('scripts')
    <script src="{{ asset('js/partials/create-offer-validator.js') }}"></script>
    <script src="{{ asset('js/partials/create-offer-sender.js') }}"></script>
    <script src="{{ asset('js/partials/datetimepicker.js') }}"></script>
    <script src="{{ asset('js/partials/control-range.js') }}"></script>
    <script src="{{ asset('js/partials/image-uploader.js') }}"></script>
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
                    lat: 0,
                    lng: 0
                };
                mapInitialize( GPS, defaultZoom );
            }


            function mapInitialize( GPS, defaultZoom ) {

                let map = L.map( 'mapid', {
                    center: GPS,
                    zoom:   defaultZoom// 13
                } );

                L.tileLayer( '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
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
                    console.log(map.getZoom());
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
                handleForm(map);
            }
        } );

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
                        type: "POST",
                        url: $('#createOfferForm').attr('action'),
                        headers: {
                            'Accept':'application/json',
                        },
                        data: formData,
                        success: function(data, textStatus, xhr){
                            if (202 == xhr.status){
                                return window.location.replace("{{ route('advert.offers.index') }}");
                            } else {
                                console.log(xhr.status);
                            }
                        },
                        error: function(resp){
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
