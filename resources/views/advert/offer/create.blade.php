@extends('advert.layout')

@section('title', 'Create offer')

@section('content')

<div class="container">
    <div class="row">

        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

            <h1>Create offer</h1>

            <form action="{{route('advert.offers.store')}}" method="post" class="nau-form" id="createOfferForm" target="_top">

                <ul class="tab-step-control js-tabs">
                    <li class="active"><a href="#tab_step1" data-toggle="tab"><em>1</em> Main<br>Information</a></li>
                    <li><a href="#tab_step2" data-toggle="tab"><em>2</em> Working Dates<br>&amp; Times</a></li>
                    <li><a href="#tab_step3" data-toggle="tab"><em>3</em> Working<br>Area</a></li>
                    <li><a href="#tab_step4" data-toggle="tab"><em>4</em> Additional<br>Settings</a></li>
                </ul>

                <div class="tab-content tab-step-content">
                    @include('advert.offer.create-step1')
                    @include('advert.offer.create-step2')
                    @include('advert.offer.create-step3')
                    @include('advert.offer.create-step4')
                </div>

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
                                alert('Get categories: there was an error 400');
                            }
                            else {
                                alert('Get categories: something else other than 200 was returned');
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

        $('#createOfferForm').on('submit', function (e){
            e.preventDefault();

            var timeframes = [];
            if (true === $('#tab_wdt1').hasClass('active')){
                var weekdays = {
                    "all" : ["mo", "tu", "we", "th", "fr", "su", "sa"],
                    "working" : ["mo", "tu", "we", "th", "fr"],
                    "weekend" : ["su", "sa"]
                };
                var workingDaysState = $('input[name="____wd_working_days"]').is(':checked');
                var weekendDaysState = $('input[name="____wd_weekend"]').is(':checked');
                var startTime = $('input[name="start_time"]').val();
                var finishTime = $('input[name="finish_time"]').val();
                if(true === workingDaysState && true === weekendDaysState) {
                    timeframes.push(compactTimeframe(weekdays.all, startTime, finishTime));
                } else if (true === workingDaysState) {
                    timeframes.push(compactTimeframe(weekdays.working, startTime, finishTime));
                } else if (true === weekendDaysState){
                    timeframes.push(compactTimeframe(weekdays.weekend, startTime, finishTime));
                }
            } else if (true === $('#tab_wdt2').hasClass('active')){
                var weekdays = {};
                $('[data-relation="check_wd8"]:checked, [data-relation="check_wd9"]:checked').each(function(){
                    currentWeekday = $(this).data('weekday');
                    timeFrom = $('[data-relation="time_wd8f"][data-weekday="' + currentWeekday + '"]').val();
                    timeTo = $('[data-relation="time_wd8t"][data-weekday="' + currentWeekday + '"]').val();
                    key = timeFrom + '-' + timeTo;
                    weekdays[key] = (Array.isArray(weekdays[key])) ? weekdays[key].concat(currentWeekday) : new Array(currentWeekday);
                });
                $.each(weekdays, function(times, days){
                    timesArray = times.split('-');
                    timeframes.push(compactTimeframe(days, timesArray[0], timesArray[1]));
                });
            }
console.log(timeframes);
            var formData = $(this).serializeArray();
            formData.push({"name" : "timeframes", "value" : timeframes});

            console.log(formData);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                headers: {
                    'Content-Type':'application/json'
                },
                data: formData,
                dataType: "json",
                success: function(data)
                      {
                          console.log(data);
                      }
            });

            function compactTimeframe(days, from, to){
                return {
                    "from": from + ':00.000000+0000',
                    "to": to + ':00.000000+0000',
                    "days": days
                };
            }
        });

	</script>
@endpush

@stop
