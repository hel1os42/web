@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-xs-12 dashboard-advert-header" style="background-image: url({{ asset('img/advert_img.png') }});">
            <div class="offer-logo"><img src="{{ $authUser['picture_url'] }}" alt="offer name"></div>
            <div class="advert-header-wrap">
                <div class="advert-header">
                    @if($isPlaceCreated)
                        <div class="create-offer">
                            <a href="{{ route('advert.offers.create') }}" class="btn-nau btn-create-offer">Create offer</a>
                        </div>
                    @endif
                    <div class="advert-info">
                        <p class="advert-name">{{ $authUser['name'] }}</p>
                        <p>{{ $authUser['phone'] }}, {{ $authUser['email'] }}</p>
                    </div>
                    <div class="stat-info clearfix"><!-- not need .row -->
                        <div class="col-xs-4">
                            <span class="icon-offers">Offers:</span>
                            <strong>{{ $total }}</strong>
                        </div>
                        <div class="col-xs-4">
                            <span class="icon-nau">NAU:</span>
                            <strong>{{ $authUser['accounts']['NAU']['balance'] }}</strong>
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
                                <td>{{ $counter++ }}</td>
                                <div class="gps" data-offerid="{{$offer['id']}}" data-lat="{{ $offer['latitude'] }}" data-lng="{{ $offer['longitude'] }}"></div>
                                <td class="details-control"><span class="button-details"><img src="{{ $offer['picture_url'] }}" alt="offer picture" onerror="imgError(this);"></span></td>
                                <td>{{ $offer['label'] }}</td>
                                <td><span data-df="yyyy/mm/dd">{{ $offer['start_date'] }}</span> &nbsp;&mdash;&nbsp; <span data-df="yyyy/mm/dd">{{ $offer['finish_date'] }}</span></td>
                                <td>{{ $offer['reward'] }}</td>
                                <td>{{ $offer['reserved'] }}</td>
                                <td>
                                    {{ $offer['status'] }}
                                </td>
                                <td>
                                    @if($offer['status'] === 'deactive')
                                        <form action="{{route('advert.offer.updateStatus', $offer['id'])}}" method="post"
                                              style="display:  inline-block;">
                                            {{ csrf_field() }}
                                            {{ method_field('PUT') }}
                                            <input type="hidden" name="status" value="active">
                                            <button style="display:  inline-block;" type="submit" class="btn btn-warning">activate</button>
                                        </form>
                                    @else
                                        <form action="{{route('advert.offer.updateStatus', $offer['id'])}}" method="post"
                                              style="display:  inline-block;">
                                            {{ csrf_field() }}
                                            {{ method_field('PUT') }}
                                            <input type="hidden" name="status" value="deactive">
                                            <button style="display:  inline-block;" type="submit" class="btn btn-primary">deactivate</button>
                                        </form>
                                    @endif
                                </td>
                                <td class="details-code" style="display: none;">
                                    <div>
                                        <div class="row set">
                                            <div class="col-xs-6">
                                                <p class="row"><span class="title col-xs-3">Description:</span> <span class="col-xs-9">{{ $offer['description'] }}</span></p>
                                                <p class="row"><span class="title col-xs-3">Location:</span> <span class="col-xs-9">{{ $offer['country'] }}, {{ $offer['city'] }} (radius: {{ $offer['radius'] / 1000 }} km)<br>{{ $offer['latitude'] }}, {{ $offer['longitude'] }}</span></p>
                                                <p class="row"><span class="title col-xs-3">Category:</span> <span class="col-xs-9" data-fix-category="true" data-uuid="{{ $offer['category_id'] }}">{{ $offer['category_id'] }}</span></p>
                                            </div>
                                            <div class="col-xs-6">
                                                <p class="row"><span class="title col-xs-4">Offer Picture:</span> <span class="col-xs-8"><img id="img-{{ $offer['id'] }}" src="{{ $offer['picture_url'] }}" alt="offer picture" class="offer-picture"  onerror="imgError(this);"></span></p>
                                                @include('partials.offer-picture-filepicker', ['offerId' => $offer['id']])
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
                                                            <span class="col-xs-8 workingDaySpan" data-day="{{ $dayKey }}" data-offerid="{{ $offer['id'] }}"></span>
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
                                                    &nbsp;<br>
                                                    <a href="{{ route('advert.offers.edit', $offer['id']) }}" class="btn-nau">Edit information</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @include('pagination.advert')
            <!-- if (have_childrens_offers) -->
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
    <script>
        window.addEventListener('load', function(){

            dataTableCreate('#table_your_offers');
//            dataTableCreate('#table_childrens_offers');

            /* date-time format */
            $('[data-df]').each(function(){
                $(this).text(dateFormat($(this).text(), $(this).data('df')));
            });

            /* offer_category */
            (function(){
                let xhr = new XMLHttpRequest();

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            let categories = JSON.parse(xhr.responseText).data;
                                $.each(categories, function(){
                                    let name = this.name;
                                    $('[data-uuid="'+this.id+'"]').each(function(){
                                        this.innerHTML = [name];
                                    }, name)
                                });
                        }
                        else {
                            console.log('Get categories:' + xhr.status);
                        }
                    }
                };

                xhr.open("GET", "{{ route('categories') }}", true);
                xhr.setRequestHeader("Accept", "application/json");
                xhr.send();
            })();

            function dataTableCreate(selector){
                let $table = $(selector);
                if ($table.length) {
                    /* create table */
                    let dt_table = $table.DataTable();

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

        });

        function fillTimeframes(){
            $(".gps").each(function(){
                let data = {
                    lat: $(this).data('lat'),
                    lng: $(this).data('lng'),
                    offerId: $(this).data('offerid')
                };
                getTZ(data, fillTimeframesCallback);
            });
            function getTZ(data, callback){
                let googleApiKey = 'AIzaSyBDIVqRKhG9ABriA2AhOKe238NZu3cul9Y';
                let url = 'https://maps.googleapis.com/maps/api/timezone/json?';
                let timestamp = Math.round(new Date().valueOf() / 1000);
                let lat = data.lat;
                let lng = data.lng;
                let requestUrl = url + `location=${lat}, ${lng}&timestamp=${timestamp}&key=${googleApiKey}`;
                return httpGetAsync(data, requestUrl, callback);

                function httpGetAsync(data, theUrl, callback)
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
                            callback(data, tz);
                        }
                    }
                    xmlHttp.open("GET", theUrl, true);
                    xmlHttp.send(null);
                }
            }


            function fillTimeframesCallback(data, tz){
                let absRawInHr = tz.substr(-4,2);
                let tzInHr = ('-' === tz.substr(0,1)) ? -1*(+absRawInHr) : (+absRawInHr);

                let oldweekdays = $('.workingDaysStorage[data-offerid="' + data.offerId + '"]').data('workingdays');
                $('.workingDaySpan[data-offerid="'+data.offerId+'"]').each(function(){
                    let day = $(this).data('day');
                    if (day in oldweekdays){
                        $(this).text(addTz(oldweekdays[day].from, tzInHr) + '-' + addTz(oldweekdays[day].to, tzInHr));
                    }
                });

                function addTz(timeStr, tzNum)
                {
                    let timeHrsNum = +timeStr.substr(0,2);
                    return (timeHrsNum+tzNum) + timeStr.substr(2);
                }
            }
        }
        fillTimeframes();
    </script>

    <script>
        function imgError(image) {
            image.onerror = "";
            image.src = "/img/imagenotfound.svg";
            return true;
        }
    </script>
@endpush

@stop
