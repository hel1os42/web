@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container">
    <script>
        function imgError(image) {
            image.onerror = "";
            image.src = "/img/imagenotfound.svg";
            return true;
        }
    </script>
    <div class="row">
        @php
            $coverUrl = $place instanceof \App\Models\Place ? $place->getOwnOrDefaultCoverUrl() : \App\Models\Place::getDefaultCoverUrl();
        @endphp
        <div class="col-sm-12 dashboard-advert-header" style="background-image: url({{ $coverUrl }});">
            <div class="offer-logo col-sm-3">
                @if( $place instanceof \App\Models\Place)
                    <img src="{{route('places.picture.show', [$place->getKey(), 'picture'])}}" onerror="imgError(this)" style="position: absolute;"><br>
                @endif
            </div>
            <div class="advert-header-wrap">
                <div class="advert-header">
                    @if($isPlaceCreated)
                        <div class="create-offer text-right">
                            <a href="{{ route('advert.offers.create') }}" class="btn-nau btn-create-offer">Create offer</a>
                        </div>
                    @endif
                    @if($place instanceof \App\Models\Place)
                        <p>{{ $place->getName() }}</p>
                        <p>{{ $place->getAddress() }}</p>
                        <p>{{ str_limit($place->getDescription(), 300) }}</p>
                        @if(!auth()->user()->isImpersonated())
                            <p>
                                <a href="{{ route('places.edit', $place) }}">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Place
                                </a>
                            </p>
                        @endif
                    @else
                        <div class="advert-info">
                            <p class="advert-name">{{ $authUser['name'] }}</p>
                            <p>{{ $authUser['phone'] }}, {{ $authUser['email'] }}</p>
                        </div>
                    @endif
                    <div class="stat-info clearfix"><!-- not need .row -->
                        <div class="col-xs-4">
                            <span class="icon-offers">Offers:</span>
                            <strong>{{ $total }}</strong>
                        </div>
                        <div class="col-xs-4">
                            <span class="icon-nau">NAU:</span>
                            <strong id="nau_balance" data-balance="{{ $authUser['accounts']['NAU']['balance'] }}">{{ $authUser['accounts']['NAU']['balance'] }}</strong>
                        </div>
                        @if(false)
                            <div class="col-xs-4">
                                <span class="icon-statistic">Statistic:</span>
                                <strong>??? 1</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab_your_offers">Your Offers</a></li>
        </ul>

        <div class="tab-content">

            <div id="tab_your_offers" class="tab-pane fade in active">

                <table id="table_your_offers" class="display">
                    <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="100">Offer</th>
                        <th>Label</th>
                        <th>Working dates</th>
                        <th>Reward</th>
                        <th>Reserved</th>
                        <th>Status</th>
                        <th>Change status</th>
                        <th style="display: none;">Details</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Offer</th>
                        <th>Label</th>
                        <th>Working dates</th>
                        <th>Reward</th>
                        <th>Reserved</th>
                        <th>Status</th>
                        <th>Change status</th>
                        <th style="display: none;">Details</th>
                    </tr>
                    </tfoot>
                    <tbody>
                        @php
                            $counter = $from;
                        @endphp
                        @foreach ($data as $offer)
                            <tr>
                                <td>
                                    {{ $counter++ }}
                                    <div class="gps" data-offerid="{{$offer['id']}}" data-lat="{{ $offer['latitude'] }}" data-lng="{{ $offer['longitude'] }}"></div>
                                </td>
                                <td class="details-control"><span class="button-details"><img src="{{ $offer['picture_url'] }}" alt="offer picture" width="32" onerror="imgError(this);"></span></td>
                                <td>{{ $offer['label'] }}</td>
                                <td><span class="js-date-convert">{{ $offer['start_date'] }}</span> &nbsp;&mdash;&nbsp; <span class="js-date-convert">{{ $offer['finish_date'] }}</span></td>
                                <td>{{ $offer['reward'] }}</td>
                                <td>{{ $offer['reserved'] }}</td>
                                <td class="offer-status"><span class="offer-status-text">{{ $offer['status'] }}</span></td>
                                <td class="details-code" style="display: none;">
                                    <div>
                                        <div class="row set">
                                            <div class="col-xs-6">
                                                <p class="row"><span class="title col-xs-3">Description:</span> <span class="col-xs-9">{{ $offer['description'] }}</span></p>
                                                <p class="row"><span class="title col-xs-3">Location:</span> <span class="col-xs-9">{{ $offer['country'] }}, {{ $offer['city'] }} (radius: {{ $offer['radius'] / 1000 }} km)<br>{{ $offer['latitude'] }}, {{ $offer['longitude'] }}</span></p>
                                                <p class="row"><span class="title col-xs-3">Category:</span> <span class="col-xs-9 category-id" data-fix-category="true" data-uuid="{{ $offer['category_id'] }}">{{ $offer['category_id'] }}</span></p>
                                            </div>
                                            <div class="col-xs-6">
                                                <p class="row"><span class="title col-xs-4">Offer Picture:</span> <span class="col-xs-8"><img id="img-{{ $offer['id'] }}" src="{{ $offer['picture_url'] }}" alt="offer picture" class="offer-picture"  onerror="imgError(this);"></span></p>
                                            </div>
                                        </div>
                                        <div class="row set">
                                            <div class="col-xs-4">
                                                @php
                                                $week = ['su' => 'Sunday', 'mo' => 'Monday', 'tu' => 'Tuesday', 'we' => 'Wednesday','th' => 'Thursday', 'fr' => 'Friday', 'sa' => 'Saturday'];
                                                $workingDays = [];
                                                    foreach ($offer['timeframes'] as $timeframe){
                                                        foreach ($timeframe['days'] as $day){
                                                            $workingDays[$day] = [
                                                                'from' => substr($timeframe['from'], 0, 5),
                                                                'to' => substr($timeframe['to'], 0, 5),
                                                                'daystr' => (array_key_exists($day, $week)) ? $week[$day] : ''
                                                            ];
                                                        }
                                                    }
                                                @endphp
                                                <p class="title">Working time:</p>
                                                <div class="workingDaysStorage" data-workingdays="{{json_encode($workingDays)}}" data-offerid="{{ $offer['id'] }}">
                                                    @foreach($workingDays as $dayKey => $workingDay)
                                                        <p class="row">
                                                            <span class="title col-xs-4">{{$workingDay['daystr']}}:</span>
                                                            <span class="col-xs-8 workingDaySpan" data-day="{{ $dayKey }}"></span>
                                                        </p>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-xs-8">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <p class="title">Max redemption total:</p>
                                                        <p class="row"><span class="title col-xs-4">Overral:</span> <span class="col-xs-8">{{ $offer['max_count'] }}</span></p>
                                                        <p class="row"><span class="title col-xs-4">Daily:</span> <span class="col-xs-8">{{ $offer['max_per_day'] }}</span></p>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <p class="title">Max redemption per user:</p>
                                                        <p class="row"><span class="title col-xs-4">Overral:</span> <span class="col-xs-8">{{ $offer['max_for_user'] }}</span></p>
                                                        <p class="row"><span class="title col-xs-4">Daily:</span> <span class="col-xs-8">{{ $offer['max_for_user_per_day'] }}</span></p>
                                                        <p class="row"><span class="title col-xs-4">Weekly:</span> <span class="col-xs-8">{{ $offer['max_for_user_per_week'] }}</span></p>
                                                        <p class="row"><span class="title col-xs-4">Monthly:</span> <span class="col-xs-8">{{ $offer['max_for_user_per_month'] }}</span></p>
                                                        <p>&nbsp;</p>
                                                        <p class="row"><span class="title col-xs-4">User level:<br><small>(min)</small></span> <span class="col-xs-8">{{ $offer['user_level_min'] }}</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                @if(false)
                                                    <p class="row"><span class="title col-xs-3">Created at:</span> <span class="col-xs-9" data-df="yyyy/mm/dd hh:MM:ss">{{ $offer['created_at'] }}</span></p>
                                                    <p class="row"><span class="title col-xs-3">Updated at:</span> <span class="col-xs-9" data-df="yyyy/mm/dd hh:MM:ss">{{ $offer['updated_at'] }}</span></p>
                                                @endif
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="pull-right">

                                                    <form method="POST" action="{{ route('advert.offers.destroy', $offer['id']) }}" style="display: inline-block; margin-right: 16px;">
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <input name="_token" type="hidden" value={{ csrf_token() }}>
                                                        <input class="btn btn-danger" type="submit" value="Delete offer">
                                                    </form>

                                                    <a href="{{ route('advert.offers.edit', $offer['id']) }}" class="btn-nau">Edit information</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="offer_status_control osc_{{ $offer['status'] }}">
                                    <form action="{{ route('advert.offer.updateStatus', $offer['id']) }}" method="PUT">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="status" value="{{ $offer['status'] === 'active' ? 'deactive' : 'active' }}">
                                        <button type="submit" class="b-activate btn btn-xs btn-primary" data-reserved="{{ $offer['reserved'] }}">activate</button>
                                        <button type="submit" class="b-deactivate btn btn-xs btn-warning">deactivate</button>
                                        <span class="waiting-response"><img src="{{ asset('img/loading.gif') }}" alt="wait..."></span>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @include('pagination.advert')
            </div>

        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/dashboard-advert-header.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.fix.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/leaflet/leaflet.nau.js') }}"></script>
    <script>

        /* dataTable */
        dataTableCreate('#table_your_offers');

        /* date and time */
        datesSetFormat();
        fillTimeframes();

        /* offer category */
        offerCategory();

        /* offer status buttons */
        offerStatusControl();

        /* page navigation optimizer */
        pagenavyCompact(document.getElementById('table_pager'));

        /* disabling button "activate" when not enough NAU */
        disableButtonActivate();

        function dataTableCreate(selector){
            let $table = $(selector);
            if ($table.length) {
                /* create table */
                let dt_table = $table.DataTable({
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": false,
                    "bAutoWidth": false,
                    "searching": false
                });
                /* отключаем родную пагинацию, поиск и т.п. - до лучших времён */

                /* show/hide details */
                $table.on('click', 'td.details-control', function(){
                    let $tr = $(this).closest('tr');
                    let row = dt_table.row($tr);
                    if (row.child.isShown()) {
                        $tr.next().children('td').children('div').slideUp(function(){
                            row.child.hide();
                            $tr.removeClass('shown');
                        });
                    } else {
                        row.child($tr.children('td.details-code').html()).show();
                        $tr.addClass('shown').next().children('td').children('div').hide().slideDown();
                    }
                });
            }
        }

        function datesSetFormat(){
            $('.js-date-convert').each(function(){
                let val = $(this).text();
                $(this).html(val ? val.substr(0, 10) : '&#8734;');
            });
        }

        function fillTimeframes(){
            $(".gps").each(function(){
                let gps = {
                    lat: $(this).data('lat'),
                    lng: $(this).data('lng')
                };
                let offerId = $(this).data('offerid');
                getTimeZoneGPS(gps, fillTimeframesCallback);

                function fillTimeframesCallback(tz){
                    /* TODO: когда-нибудь переделать чтоб понимало таймзоны с отличием в 15-30 мин. */
                    let $box = $(`.workingDaysStorage[data-offerid="${offerId}"]`);
                    let tf = $box.data('workingdays');
                    $box.find('.workingDaySpan').each(function(){
                        let day = $(this).data('day');
                        $(this).text(getTime(tf[day].from, tz) + ' - ' + getTime(tf[day].to, tz));
                    });

                    function getTime(time, tz){
                        let h = +time.substr(0, 2) + +tz.substr(0, 3);
                        let m = +time.substr(3, 2) + +(tz[0] + tz.substr(3, 2));
                        if (m > 59) { m -=60; h++; }
                        if (m < 0) { m +=60; h--; }
                        if (m < 10) m = '0' + m;
                        if (h > 23) h -= 24;
                        if (h < 0) h += 24;
                        if (h < 10) h = '0' + h;
                        return h + ':' + m;
                    }
                }
            });
        }

        function offerStatusControl(){
            $('.offer_status_control form').on('submit', function(e){
                e.preventDefault();

                let $nau_balance = $('#nau_balance');
                let $box = $(this).parents('.offer_status_control');
                let $offer_status = $box.find('[name="status"]');
                let $err = $box.find('.waiting-response');

                let deltaNau = parseFloat($box.find('.b-activate').attr('data-reserved'));
                if ($offer_status.val() === 'active') deltaNau = -deltaNau;
                setNauBalance($nau_balance, deltaNau);

                $box.removeClass('osc_active osc_deactive').addClass('osc_wait');
                let formData = $(this).serializeArray();
                console.log('Change Offer Status:');
                console.dir(formData);

                disableButtonActivate();
                /* делаем красиво изменение баланса */
                balanceFineChanging();

                $.ajax({
                    method: "PUT",
                    url: $(this).attr('action'),
                    headers: { 'Accept':'application/json' },
                    data: formData,
                    success: function(data, textStatus, xhr){
                        if (202 === xhr.status){
                            $box.parent().children('.offer-status').find('.offer-status-text').text($offer_status.val());
                            $box.removeClass('osc_wait').addClass('osc_' + $offer_status.val());
                            $offer_status.val($offer_status.val() === 'active' ? 'deactive' : 'active');
                            /* проверяем доступность кнопок "activate" */
                            disableButtonActivate();
                        } else {
                            setNauBalance($nau_balance, -deltaNau);
                            disableButtonActivate();
                            balanceFineChanging();
                            $err.text('err-st: ' + xhr.status);
                            console.dir(xhr);
                        }
                    },
                    error: function(resp){
                        setNauBalance($nau_balance, -deltaNau);
                        disableButtonActivate();
                        balanceFineChanging();
                        $err.text('err-st: ' + resp.status);
                        console.dir(resp);
                        alert(`Error ${resp.status}: ${resp.responseText}`);
                    }
                });
            });
            function setNauBalance($nau_balance, delta){
                $nau_balance.attr('data-balance', parseFloat($nau_balance.attr('data-balance')) + delta);
            }
            function balanceFineChanging(){
                let duration = 1000, frame = 50, start_time = Date.now();
                let $nau = $('#nau_balance');
                let $naus = $nau.add('#header_nau_balance');
                let nau = parseFloat($nau.attr('data-balance'));
                let nau_text = parseFloat($nau.text());
                let delta = (nau - nau_text) / duration * frame;
                let timerID = $nau.attr('data-timerid');
                if (timerID) clearInterval(parseInt(timerID));
                $nau.attr('data-timerid', setInterval(function(){
                    $naus.text(Math.round(nau_text += delta));
                    if (Date.now() > start_time + duration) {
                        clearInterval(parseInt($nau.attr('data-timerid')));
                        $naus.text(nau);
                    }
                }, frame));
            }
        }

        function offerCategory(){
            srvRequest("{{ route('categories') }}", 'GET', 'json', function(response){
                $('.category-id').each(function(){
                    let uuid = $(this).text();
                    $(this).text(response.data.find(function(e){ return e.id === uuid; }).name);
                });
            });
        }

        function disableButtonActivate(){
            let nau = parseFloat(document.querySelector('#nau_balance').dataset.balance);
            document.querySelectorAll('.offer_status_control .b-activate').forEach(function(btn){
                let reserved = parseFloat(btn.dataset.reserved);
                btn.disabled = reserved > nau;
            });
        }
    </script>
@endpush

@stop
