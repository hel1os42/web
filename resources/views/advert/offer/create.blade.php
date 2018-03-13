@extends('layouts.master')

@section('title', 'Create offer')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

            <h1>Create offer</h1>

            <form action="{{ route('advert.offers.store') }}" method="POST" class="nau-form" id="createOfferForm" target="_top">

                @include('advert.offer.create-main-info')
                @include('partials/offer-picture-filepicker')
                @include('advert.offer.create-category')
                @include('advert.offer.create-map')
                @include('advert.offer.create-working')
                @include('advert.offer.create-redemption')

            </form>

        </div>
    </div>
</div>


@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('js/summernote/summernote.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/datetimepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/offer-more.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/cropper/imageuploader.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/cropper/cropper.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('js/formdata.min.js') }}"></script>
    <script src="{{ asset('js/partials/datetimepicker.js') }}"></script>
    <script src="{{ asset('js/partials/control-range.js') }}"></script>
    <script src="{{ asset('js/partials/offer-more.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.nau.js') }}"></script>
    <script src="{{ asset('js/cropper/imageuploader.js') }}"></script>
    <script src="{{ asset('js/cropper/cropper.js') }}"></script>
    <script>

        const RESERVATION_MULTIPLIER = 10;

        /* dateTime picker init */
        dateTimePickerInit();

        /* control range init */
        controlRangeInit();
        maxRedemptionInfinity('.max-redemption input');

        /* "Working Days & Time" checking days */
        checkingDays();

        /* synchronization of wokr time */
        wokrTimeSynchronization();

        /* offer type = discount */
        offerTypeController();

        /* you can not input more than N characters in this fields */
        setFieldLimit('[data-max-length]');

        /* offer description More */
        /*offerMoreInit('more_wrap');*/
        /*
            let moreTextForTranslate = {
                hashButtons: 'You can use next tags for create links to additional information',
                title: 'More information',
                addButton: 'Add item',
                ...
            };
            offerMoreInit('more_wrap', moreTextForTranslate);
        */

        workingAreaWhenDelivery();

        /* picture */
        imageUploader('#offer_image_box .image-box');
        let $offer_image_box = $('#offer_image_box');
        $offer_image_box.find('[type="file"]').on('change', function(){
            $(this).attr('data-changed', 'true');
            console.log('Picture changed');
            $offer_image_box.find('.image').attr('data-cropratio', '1');
        });
        $offer_image_box.find('.image').on('load', function(){
            $(this).parents('.img-hide').removeClass('img-hide');
            if (this.dataset.cropratio) {
                imageCropperRemove(this);
                imageCropperInit(this);
            }
        });

        /* map */
        mapInit({
            id: 'mapid',
            setPosition: {
                lat: $('[name="latitude"]').val(),
                lng: $('[name="longitude"]').val(),
                radius: $('[name="radius"]').val()
            },
            done: mapDone,
            move: mapMove
        });

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
            $startDate.on('change', function(){
                let fdate = $finishDate.val();
                if (fdate) {
                    let sdate = new Date($startDate.val());
                    fdate = new Date(fdate);
                    if (sdate > fdate) { $finishDate.val(''); }
                }
            });
            $('.cleartime-btn').on('click', function(){
                $('[name="finish_date"]').val('');
            });
        }

        function controlRangeInit(){
            let selector = '.js-numeric';
            $('.js-numeric').parents('label').before(btn('more', '+')).after(btn('less', '-'));
            controlRange(selector);
            maxRedemptionInfinity('.max-redemption input');
            $('[name="reward"]').on('change', function(){
                let $reserv = $('[name="reserved"]');
                let reservMin = $(this).val() * RESERVATION_MULTIPLIER;
                $reserv.attr('data-min', reservMin);
                if ($reserv.val() < reservMin) {
                    $reserv.attr('data-default', reservMin).val(reservMin);
                }
            });
            function btn(c, t) { return `<em role="button" class="${c}">${t}</em>`; }
        }

        function checkingDays(){
            $('.day-info').each(function(){
                let $p = $(this);
                let $cb = $p.find('[type="checkbox"]');
                if (!$cb.is(':checked')) $p.addClass('passive');
                $cb.on('change', function(){
                    $p.toggleClass('passive', !$(this).is(':checked'));
                }).trigger('change');
            });
            $('#selectWorkingDays').add('#selectWeekends').on('click', function(){
                $('.day-info').each(function(){
                    let $cb = $(this).find('[type="checkbox"]');
                    $(this)[($cb.is(':checked') ? 'remove' : 'add') + 'Class']('passive');
                });
            });
        }

        function wokrTimeSynchronization(){
            $('.day-info').find('[name^="start_time_"], [name^="finish_time_"]').on('change', function(){
                let name = $(this).attr('name').substr(0,8);
                let val = $(this).val();
                $(this).parents('.day-info').nextAll('.day-info').each(function(){
                    if ($(this).find('[type="checkbox"]').prop('checked')) {
                        let input = $(this).find(`[name^="${name}"]`);
                        if (!input.val()) input.val(val);
                    }
                });
            });
        }

        function offerTypeController(){
            let $rads = $('.offer-type-box').find('[type="radio"]');
            $rads.on('change', function(){
                $rads.each(function(){
                    $(this).parents('p').next('.sub-radio')['slide' + ($(this).is(':checked') ? 'Down' : 'Up')]();
                });
            }).trigger('change');
        }

        function mapDone(map){
            mapMove(map);
            $('#createOfferForm').on('submit', function (e) {
                e.preventDefault();
                if (formValidation()) {
                    getTimeZone(map, getFormData);
                }
            });
            validationOnFly();
            /* set map position by GPS or Address */
            setMapPositionByGpsOrAddress(map);
        }

        function mapMove(map){
            let values = mapValues(map);
            $('#mapradius').children('span').text(values.radius / 1000);
            let $latitude = $('[name="latitude"]');
            let $longitude = $('[name="longitude"]');
            $latitude.val(values.lat);
            $longitude.val(values.lng);
            $('[name="radius"]').val(values.radius);
            getTimeZone(map, function(tz){
                $('[name="timezone"]').val(tz);
                $('#map_box').toggleClass('invalid', tz === 'error')
            });
        }

        function getFormData(tz){
            let formData = $('.formData').serializeArray();

            formData.push({
                "name" : "_token",
                "value" : $('[name="_token"]').val()
            });

            $('.nullableLimit').each(function(){
                formData.push({
                    "name" : $(this).attr('name'),
                    "value" : $(this).val() === '0' ? null : $(this).val()
                });
            });
            $('.nullableFormData').each(function(){
                let val = $(this).val();
                if (val === '0' || val === '' || val === undefined) val = null;
                formData.push({
                    "name" : $(this).attr('name'),
                    "value" : val
                });
            });

            /* offer type */
            formData.push({
                "name" : "type",
                "value" : $('[name="offer_type"]:checked').val()
            });
            formData.push({
                "name" : "delivery",
                "value" : $('[name="delivery"]').prop('checked') ? "1" : "0"
            });
            let gift_bonus_descr = '';
            if ($('#bonus_radio').prop('checked')) gift_bonus_descr = $('#bonus_information').val();
            if ($('#gift_radio').prop('checked')) gift_bonus_descr = $('#gift_information').val();
            if (gift_bonus_descr) {
                formData.push({
                    "name" : "gift_bonus_descr",
                    "value" : gift_bonus_descr
                });
            }

            /* working dates */
            let startDateVal = $('[name="start_date"]').val();
            formData.push({
                "name" : "start_date",
                "value" : prepareDate(new Date(startDateVal), tz)
            });
            let finishDateVal = $('[name="finish_date"]').val();
            formData.push({
                "name" : "finish_date",
                "value" : '' === finishDateVal ? null : prepareDate(new Date(finishDateVal), tz)
            });

            /* working times */
            $('#dayInfoBox').find('[type="checkbox"]:checked').each(function(i){
                let $cb = $(this), $day = $(this).parents('.day-info');
                formData.push({
                    "name" : `timeframes[${i}][from]`,
                    "value" : $day.find('[name^="start_time"]').val() + ':00.000000' + tz
                });
                formData.push({
                    "name" : `timeframes[${i}][to]`,
                    "value" : $day.find('[name^="finish_time"]').val() + ':00.000000' + tz
                });
                formData.push({
                    "name" : `timeframes[${i}][days][]`,
                    "value" : $cb.val()
                });
            });

            console.dir(formData);

            waitPopup(true);
            $('#waitRequests').text('1');

            $.ajax({
                method: "POST",
                url: $('#createOfferForm').attr('action'),
                headers: { 'Accept':'application/json' },
                data: formData,
                success: function(data, textStatus, xhr){
                    if (202 === xhr.status){
                        if ($offer_image_box.find('[type="file"]').attr('data-changed')) {
                            $('#waitRequests').text(2);
                            let uuid = xhr.getResponseHeader('Location').split('/');
                            sendImage(uuid[uuid.length - 1]);
                        } else {
                            window.location.replace("{{ route('advert.offers.index') }}?orderBy=updated_at&sortedBy=desc");
                        }
                    } else {
                        $('#waitError').text('Status: ' + xhr.status);
                        console.log(xhr);
                    }
                },
                error: function(resp){
                    $('#waitError').text(`Error ${resp.status}: ${resp.responseText}`);
                    console.log(resp);
                }
            });

            function prepareDate(date, tz) {
                return date.getFullYear() + "-" + prependWithZero(date.getMonth() + 1) + "-" + prependWithZero(date.getDate()) + ' 00:00:00.000000' + tz;
                function prependWithZero(n) { return ("0" + n).slice(-2); }
            }

        }

        function validationOnFly() {

            /* Offer Type */

            $('[name="discount_percent"]').on('change', function(){
                /* string to integer or float with 1-2 digits after dot */
                let val = $(this).val().trim().replace(',', '.');
                val = !val ? '1' : val;
                let dot = val.indexOf('.');
                if (dot >= 0) {
                    let a = val.substr(0, dot);
                    let b = val.substr(dot + 1, 2);
                    a = +a === 0 ? '0' : a;
                    val = b ? a + '.' + b : a;
                }
                let test = +val;
                if (isNaN(test)) val = '1';
                else {
                    if (test > 100) val = '100';
                    else if (test < 0.01) val = '1';
                }
                $(this).val(val);
            });

        }

        function formValidation() {
            let res = true;
            let val, $control, $hint;

            /* Map */
            if ($('[name="timezone"]').val() === 'error') res = false;

            /* Times */
            let $dayInfoBox = $('#dayInfoBox');
            let $daysChecked = $dayInfoBox.find('[type="checkbox"]:checked');
            if ($daysChecked.length < 1) {
                $dayInfoBox.addClass('invalid');
                $('html, body').animate({ scrollTop: $('.days-info').prev().offset().top }, 400);
                res = false;
            }
            for (let i = 0; i < $daysChecked.length; i++) {
                let times = $daysChecked.eq(i).parents('.day-info').find('[name*="time"]');
                if (times.eq(0).val() === '') { res = emptyTimeField(times.eq(0)); break; }
                if (times.eq(1).val() === '') { res = emptyTimeField(times.eq(1)); break; }
            }
            function emptyTimeField(input){
                $dayInfoBox.addClass('invalid');
                input.focus();
                return false;
            }

            /* Working Days */
            $control = $('[name="start_date"]');
            if ($control.val() === '') {
                $control.focus().parents('.control-datetime').addClass('invalid');
                res = false;
            }

            /* Offer Type */
            $hint = $('#hint_offertypebox');
            if ($('#gift_radio').prop('checked')) {
                $control = $('#gift_information');
                if ($control.val().length < 3) {
                    $control.focus().parents('.control-text').addClass('invalid');
                    $hint.show();
                    res = false;
                }
            }
            if ($('#bonus_radio').prop('checked')) {
                $control = $('#bonus_information');
                if ($control.val().length < 3) {
                    $control.focus().parents('.control-text').addClass('invalid');
                    $hint.show();
                    res = false;
                }
            }
            if ($('#discount_radio').prop('checked')) {
                $control = $('[name="discount_percent"]');
                val = +$control.val();
                if (isNaN(val) || val < 0.01 || val > 100) {
                    $control.focus().parents('.control-text').addClass('invalid');
                    $hint.show();
                    res = false;
                }
            }

            /* Offer description */
            $control = $('[name="description"]');
            val = $control.val().length;
            if (val < 2) {
                $control.focus().parents('.control-text').addClass('invalid');
                res = false;
            }

            /* Offer name */
            $control = $('[name="label"]');
            val = $control.val().length;
            if (val < 3) {
                $control.focus().parents('.control-text').addClass('invalid');
                res = false;
            }

            return res;
        }

        function sendImage(uuid){
            let $file = $offer_image_box.find('[type="file"]');
            let $img = $offer_image_box.find('.image');
            if ($file.attr('data-changed') && $img.attr('data-crop')) {
                let formData = new FormData();
                formData.append('_token', $offer_image_box.find('[name="_token"]').val());
                let base64Data = imageCropperCrop($img.get(0)).getAttribute('src').replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
                formData.append('picture', base64toBlob(base64Data, 'image/jpeg'), 'picture.jpg');
                for(let i of formData) { console.log(i); }
                $.ajax({
                    url: `/offers/${uuid}/picture`,
                    data: formData,
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    success: function () {
                        console.log('SUCCESS: image sent.');
                        window.location.replace("{{ route('advert.offers.index') }}?orderBy=updated_at&sortedBy=desc");
                    },
                    error: function (resp) {
                        $('#waitError').text(`Error ${resp.status}: ${resp.responseText}`);
                        console.log('ERROR: image not sent.');
                    }
                });
            } else {
                window.location.replace("{{ route('advert.offers.index') }}?orderBy=updated_at&sortedBy=desc");
            }
        }

        function setMapPositionByGpsOrAddress(map){
            /* TODO: need refactoring, this code in 4-th pages */
            let $country = $('[name="country"]');
            let $city = $('[name="city"]');
            let $gps_crd = $('[name="gps_crd"]');
            setCountryCity();
            $country.add($city).on('blur', function(){ setCountryCity(); });
            $('#btn_gps_crd').on('click', function(){
                let address = $gps_crd.val().trim();
                if (address.length < 5) return false;
                address = tryConvertToGPS(address);
                if (address.lat) {
                    map.panTo(address);
                    mapMove(map);
                } else {
                    getGpsByAddress(address, function(response){
                        if (response.results.length) {
                            map.panTo(response.results[0].geometry.location);
                            mapMove(map);
                        } else {
                            $gps_crd.parents('.gps-crd-box').addClass('invalid');
                        }
                    });
                }
            });
            function setCountryCity(){
                let country = $country.val();
                let city = $city.val();
                let str = (country ? country + ', ' : '') + (city ? city + ', ' : '');
                $gps_crd.val(str);
            }
            function tryConvertToGPS(str){
                let arr = str.split(/,\s*/);
                if (arr.length !== 2) return str;
                let lat = parseFloat(arr[0]);
                let lng = parseFloat(arr[1]);
                if (isNaN(lat) || isNaN(lng)) return str;
                return {lat, lng};
            }
        }

        function workingAreaWhenDelivery(){
            let workingArea = document.getElementById('working_area');
            let checkboxDelivery = document.getElementById('check_delivery');
            if (checkboxDelivery.checked) workingArea.style.display = '';
            checkboxDelivery.addEventListener('change', function(){
                if (this.checked) $(workingArea).slideDown();
                else $(workingArea).slideUp();
            });
        }

    </script>
@endpush

@stop
