@extends('layouts.master')

@section('title', 'Edit offer')

@section('content')

<div class="container">

    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

            <h1>Edit offer</h1>

            <form action="{{ route('advert.offers.update', $id) }}" method="POST" class="nau-form" id="editOfferForm" target="_top">

                @include('advert.offer.edit-main-info')
                @include('partials/offer-picture-filepicker')
                @include('advert.offer.edit-category')
                @include('advert.offer.edit-map')
                @include('advert.offer.edit-working')
                @include('advert.offer.edit-redemption')

                @can('offers.manage_featured_options')
                    @include('advert.offer.edit-featured-options')
                @endcan

                <p class="tokens-total"><strong>{{ $authUser['accounts']['NAU']['balance'] }}</strong> <span>{{ __('offers.text.account_tokens') }}</span></p>

                <p class="clearfix"><input type="submit" class="btn-nau pull-right" value="{{ __('buttons.save') }}"></p>

            </form>

        </div>
    </div>
</div>


@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('js/summernote/summernote.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/form.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/datetimepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/leaflet/leaflet.css') }}">
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

        let offerImage404 = false;

        /* timezone */
        document.getElementsByName('timezone')[0].value = convertTimezoneOffsetFromSecToHrsMin(document.getElementsByName('timeframes_offset')[0].value);

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
        offerMoreInit('more_wrap');
        /*
            let moreTextForTranslate = {
                hashButtons: 'You can use next tags for create links to additional information',
                title: 'More information',
                addButton: 'Add item',
                ...
            };
            offerMoreInit('more_wrap', moreTextForTranslate);
        */

        /* picture */
        imageUploader('#offer_image_box .image-box');
        let $offer_image_box = $('#offer_image_box');
        $offer_image_box.find('[type="file"]').on('change', function(){
            offerImage404 = false;
            $(this).attr('data-changed', 'true');
            console.log('Picture changed');
            $offer_image_box.removeClass('invalid').find('.image').attr('data-cropratio', '1');
        });
        $offer_image_box.find('.image').on('error', function(){
            offerImage404 = true;
        }).on('load', function(){
            $(this).parents('.img-hide').removeClass('img-hide');
            if (this.dataset.cropratio) {
                imageCropperRemove(this);
                imageCropperInit(this);
            }
        }).attr('src', "{{ $picture_url }}?v={{ $updated_at }}");

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

        setCityCountry();

        function dateTimePickerInit(){
            let today = new Date();
            let $startDate = $('[name="start_date"]'),
                $finishDate = $('[name="finish_date"]');
            $startDate.on('focus click', function(){
                datePicker($(this), today);
            });
            $finishDate.on('focus click', function(){
                if (!$startDate.val()) $startDate.focus();
                else {
                    let minDate = new Date($startDate.val());
                    if (today.getTime() > minDate.getTime()) minDate = today;
                    datePicker($(this), minDate);
                }
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
            /* if reservation_token < 10 ==> reservation_token = 10 */
            let reservInput = document.getElementsByName('reserved')[0];
            let reservMin = parseInt(document.getElementsByName('reward')[0].value) * RESERVATION_MULTIPLIER;
            let reserv = parseInt(reservInput.value);
            reservInput.dataset.min = reservMin.toString();
            if (reserv < reservMin) {
                reservInput.value = reservMin;
                reservInput.dataset.default = reservMin.toString();
            }
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
            $('#working_area').removeAttr('style').css('display', 'none');
            workingAreaWhenDelivery();
            /* mapMove(map); */
            $('#editOfferForm').on('submit', function (e) {
                e.preventDefault();
                if (formValidation()) {
                    /* getTimeZoneMap(map, getFormData); */
                    getFormData();
                }
            });
            validationOnFly();
            /* getTimeZoneMap(map, fillTimeframes); */
            fillTimeframes();
            /* set map position by GPS or Address */
            addAction_setMapPositionByGpsOrAddress(map);
        }

        function mapMove(map){
            let values = mapValues(map);
            $('#mapradius').children('span').text(values.radius / 1000);
            let $latitude = $('[name="latitude"]');
            let $longitude = $('[name="longitude"]');
            $latitude.val(values.lat);
            $longitude.val(values.lng);
            $('[name="radius"]').val(values.radius);
            getTimeZoneMap(map, function(tz, tzXHR){
                $('[name="timezone"]').val(tz);
                $('[name="timeframes_offset"]').val(tzXHR.rawOffset);
                $('#map_box').toggleClass('invalid', tz === 'error');
            });
        }

        function fillTimeframes(){
            let offset = parseInt($('[name="timeframes_offset"]').val().toString());
            let offsetHours = Math.floor(offset / 3600);
            let offsetMins = Math.floor(offset / 60) % 60;
            let $box = $('#dayInfoBox');
            $box.find('[data-checked="true"]').prop('checked', true).parents('.day-info').removeClass('passive');
            $box.find('input[data-value]').each(function(){
                let val = $(this).attr('data-value');
                let h = +val.substr(0, 2) + offsetHours;
                let m = +val.substr(3, 2) + offsetMins;
                if (m > 59) { m -=60; h++; }
                if (m < 0) { m +=60; h--; }
                m = add0(m);
                if (h > 23) h -= 24;
                if (h < 0) h += 24;
                h = add0(h);
                $(this).val(h + ':' + m);
            });
            $('[name="start_date"], [name="finish_date"]').each(function(){
                let val = $(this).val().replace(' ', 'T');
                if (val.length > 1) {
                    let date = new Date(val);
                    date.setMinutes(date.getMinutes() + offsetMins);
                    date.setHours(date.getHours() + offsetHours);
                    $(this).val(date.getFullYear() + '-' + add0(date.getMonth() + 1) + '-' + add0(date.getDate()));
                }
            });
        }

        function getFormData(){
            let tz = $('[name="timezone"]').val();
            if ($('#check_delivery').is(':checked') && tz === 'error') {
                let $map = $('#map_box');
                $map.toggleClass('invalid', tz === 'error');
                $('html, body').animate({ scrollTop: $map.offset().top }, 400);
                return false;
            }

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

            /* is_featured */
            if (1 === $('[name="is_featured"]').length) {
                formData.push({
                    "name": "is_featured",
                    "value": $('[name="is_featured"]').prop('checked') ? "1" : "0"
                });
            }

            /* offer type */
            formData.push({
                "name" : "type",
                "value" : $('[name="offer_type"]:checked').val()
            });
            formData.push({
                "name" : "delivery",
                "value" : $('[name="delivery"]').prop('checked') ? "1" : "0"
            });
            let discount_start_price = parseInt($('[name="discount_start_price"]').val().toString());
            if (discount_start_price > 0) {
                formData.push({
                    "name" : "discount_start_price",
                    "value" : discount_start_price.toString()
                });
                formData.push({
                    "name" : "currency",
                    "value" : $('[name="currency"]').val()
                });
            }
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
            let startDateVal = $('[name="start_date"]').val().toString();
            formData.push({
                "name" : "start_date",
                "value" : prepareDate(new Date(startDateVal), tz)
            });
            let finishDateVal = $('[name="finish_date"]').val().toString();
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

            waitPopup(false);

            $.ajax({
                method: "PATCH",
                url: $('#editOfferForm').attr('action'),
                headers: { 'Accept':'application/json' },
                data: formData,
                success: function(data, textStatus, xhr){
                    if (202 === xhr.status){
                        sendImage();
                    } else {
                        $('#waitError').text('Status: ' + xhr.status);
                        console.dir(xhr);
                    }
                },
                error: function(resp){
                    if (401 === resp.status) UnAuthorized();
                    else if (0 === resp.status) AdBlockNotification();
                    else {
                        $('#waitError').text(`Error ${resp.status}: ${resp.responseText}`);
                        console.dir(resp);
                    }
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

            /* offer image */
            let $imgBox = $('#offer_image_box');
            if (offerImage404) {
                $imgBox.addClass('invalid');
                $('html, body').animate({ scrollTop: $imgBox.offset().top - 40 });
                res = false;
            }

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
            if (val < 3 || val > 128) {
                $control.focus().parents('.control-text').addClass('invalid');
                res = false;
            }

            return res;
        }

        function sendImage(){
            let $file = $offer_image_box.find('[type="file"]');
            let $img = $offer_image_box.find('.image');
            if ($file.attr('data-changed') && $img.attr('data-crop')) {
                let formData = new FormData();
                formData.append('_token', $('[name="_token"]').val().toString());
                let base64Data = imageCropperCrop($img.get(0)).getAttribute('src').replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
                formData.append('picture', base64toBlob(base64Data, 'image/jpeg'), 'picture.jpg');
                for(let i of formData) { console.log(i); }
                $.ajax({
                    url: "/offers/{{ $id }}/picture",
                    data: formData,
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    success: function () {
                        console.log('SUCCESS: image sent.');
                        window.location.replace("{{ route('advert.offers.index') }}?orderBy=updated_at&sortedBy=desc");
                    },
                    error: function (resp) {
                        if (401 === resp.status) UnAuthorized();
                        else if (0 === resp.status) AdBlockNotification();
                        else {
                            $('#waitError').text(resp.status);
                            console.log('ERROR: image not sent.');
                        }
                    }
                });
            } else {
                window.location.replace("{{ route('advert.offers.index') }}?orderBy=updated_at&sortedBy=desc");
            }
        }

        function addAction_setMapPositionByGpsOrAddress(map){
            let $country = $('[name="country"]');
            let $city = $('[name="city"]');
            let $gps_crd = $('[name="gps_crd"]');
            setCountryCity();
            $country.add($city).on('blur', function(){ setCountryCity(); });
            $('#btn_gps_crd').on('click', btnSearchPointClick);
            $gps_crd.on('keypress', function(e){
                if (e.keyCode === 13) { btnSearchPointClick(); return false; }
            });
            function btnSearchPointClick(){
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
            }
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

        function setCityCountry(){
            let $country = $('input[name="country"]');
            let $city = $('input[name="city"]');
            if ($country.val() && $city.val()) return;
            let lat = $('input[name="latitude"]').val();
            let lng = $('input[name="longitude"]').val();
            getAddressByGps(lat, lng, function(resp){
                if (resp.status === 'OK' && resp.results.length) {
                    resp = resp.results[0].address_components;
                    let country = resp.find(function(item){ return item.types[0] === 'country'; });
                    let city = resp.find(function(item){ return item.types[0] === 'locality'; });
                    if (country) $country.val(country.long_name);
                    if (city) $city.val(city.long_name);
                }
            })
        }

    </script>
@endpush

@stop
