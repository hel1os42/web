@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container">
    <script>
        function imgError(image) {
            image.onerror = "";
            image.src = "/img/imagenotfound.svg";
            image.style.backgroundImage = 'none';
            if (image.classList.contains('offer-picture-thumb')) {
                let parent = image.parentElement;
                while (parent && parent.tagName.toLowerCase() !== 'tr') parent = parent.parentElement;
                if (parent) parent.classList.add('offer-status-no-image');
            }
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
                    <img src="{{ route('places.picture.show', [$place->getKey(), 'picture']) }}?size=desktop" onerror="imgError(this)" style="position: absolute;"><br>
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

            <div id="tab_your_offers" class="tab-pane fade in active" data-token="{{ csrf_token() }}" data-links-url="{{ route('places.offer_links.index', auth()->user()->place->id) }}">
                <img class="data-loading" src="{{ asset('img/loading.gif') }}" alt="wait..." style="display:block; margin: 0 auto;">

                <table id="table_your_offers" class="display" style="opacity:0;">
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
                            <tr data-offer-status="{{ $offer['status'] }}">
                                <td>
                                    {{ $counter++ }}
                                    <div class="gps" data-offerid="{{ $offer['id'] }}" data-lat="{{ $offer['latitude'] }}" data-lng="{{ $offer['longitude'] }}" data-timeframesoffset="{{ $offer['timeframes_offset'] }}"></div>
                                </td>
                                <td class="details-control"><span class="button-details"><img src="{{ $offer['picture_url'] }}?size=desktop" alt="offer picture" class="offer-picture-thumb" width="32" onerror="imgError(this);"></span></td>
                                <td>{{ $offer['label'] }}</td>
                                <td class="working-period"><span class="js-date-convert" data-raw="{{ $offer['start_date'] }}">{{ $offer['start_date'] }}</span> &nbsp;&mdash;&nbsp; <span class="js-date-convert finish-date" data-raw="{{ $offer['finish_date'] }}">{{ $offer['finish_date'] }}</span></td>
                                <td>{{ $offer['reward'] }}</td>
                                <td>{{ $offer['reserved'] }}</td>
                                <td class="offer-status">
                                    <span class="offer-status-text offer-status-text-color-{{ $offer['status'] }}">{{ $offer['status'] }}</span>
                                    <span class="offer-status-text-no-image">no image</span>
                                    <span class="offer-status-text-no-nau">no NAU</span>
                                    <span class="offer-status-text-expired">expired</span>
                                </td>
                                <td class="details-code" style="display: none;">
                                    <div>
                                        <div class="row set">
                                            <div class="col-xs-6">
                                                <p class="row"><span class="title col-xs-3">Description:</span> <span class="col-xs-9 offer-description">{{ $offer['description'] }}</span></p>
                                                <p class="row"><span class="title col-xs-3">Location:</span> <span class="col-xs-9">{{ $offer['country'] }}, {{ $offer['city'] }} (radius: {{ $offer['radius'] / 1000 }} km)<br>{{ $offer['latitude'] }}, {{ $offer['longitude'] }}</span></p>
                                                <p class="row"><span class="title col-xs-3">Category:</span> <span class="col-xs-9 category-id" data-fix-category="true" data-uuid="{{ $offer['category_id'] }}">{{ $offer['category_id'] }}</span></p>
                                            </div>
                                            <div class="col-xs-6">
                                                <p class="row"><span class="title col-xs-4">Offer Picture:</span> <span class="col-xs-8"><img id="img-{{ $offer['id'] }}" src="" data-src="{{ $offer['picture_url'] }}?size=mobile" alt="offer picture" class="offer-picture"  onerror="imgError(this);"></span></p>
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
                                                <div class="workingDaysStorage" data-workingdays="{{ json_encode($workingDays) }}" data-offerid="{{ $offer['id'] }}">
                                                    @foreach($workingDays as $dayKey => $workingDay)
                                                        <p class="row">
                                                            <span class="title col-xs-4">{{ $workingDay['daystr'] }}:</span>
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
                                                        <p class="row">
                                                            <span class="title col-xs-4">
                                                                {{ __('offers.points_for_redemption') }}
                                                            </span>
                                                            <span class="col-xs-8">
                                                                {{ array_get($offer, 'points', '?') }}
                                                            </span></p>
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
                                                    <div style="display: inline-block;" class="offer-edit-button-wrapper oeb-{{ $offer['status'] }}">
                                                        <span class="btn btn-danger offer-delete-button" data-action="{{ route('advert.offers.destroy', $offer['id']) }}">Delete offer</span>
                                                        <a href="{{ route('advert.offers.edit', $offer['id']) }}" class="btn-nau offer-edit-button">Edit information</a>
                                                        <span class="offer-edit-no-button">You must deactivate the offer to delete or edit it.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="offer_status_control osc_{{ $offer['status'] }}">
                                    <form action="{{ route('advert.offer.updateStatus', $offer['id']) }}" method="POST">
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
    <script src="{{ asset('js/partials/offer-more-show.js') }}"></script>
    <script>

        /* dataTable */
        dataTableCreate('#table_your_offers');

        /* date and time */
        fillTimeframes();

        /* offer category */
        offerCategory();

        /* offer status buttons */
        offerStatusControl();

        /* page navigation optimizer */
        pagenavyCompact(document.getElementById('table_pager'));

        /* disabling button "activate" when not enough NAU */
        window.addEventListener('load', function(){ disableButtonActivate(); });

        /* delete offer with ajax */
        btnDeleteOffer();

        /* offer description links */
        offerMoreShow();

        function dataTableCreate(selector){
            let $table = $(selector);
            if ($table.length) {
                /* create table */
                let dt_table = $table.on('init.dt', function(){
                        $('.data-loading').hide();
                        $(this).animate({'opacity': "1"}, 400);
                        $('.offer-picture').each(function(){
                            $(this).attr('src', $(this).attr('data-src'));
                        });
                    })
                    .DataTable({
                        "bPaginate": false,
                        "bLengthChange": false,
                        "bFilter": true,
                        "bInfo": false,
                        "bAutoWidth": false,
                        "searching": false
                });

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

        function fillTimeframes(){
            $('.gps').each(function(){
                let offset = { s: parseInt($(this).data('timeframesoffset').toString()) };
                offset.h = Math.floor(offset.s / 3600);
                offset.m = Math.floor(offset.s / 60) % 60;
                let offerId = $(this).data('offerid');

                let $box = $(`.workingDaysStorage[data-offerid="${offerId}"]`);
                let tf = $box.data('workingdays');
                $box.find('.workingDaySpan').each(function(){
                    let day = $(this).data('day');
                    $(this).text(getTime(tf[day].from, offset) + ' - ' + getTime(tf[day].to, offset));
                });
                $box = $(this).parents('td').eq(0).siblings('.working-period');
                $box.find('.js-date-convert').each(function(){
                    let val = $(this).text().replace(' ', 'T');
                    if (val.length > 1) {
                        let date = new Date(val);
                        date.setMinutes(date.getMinutes() + offset.m);
                        date.setHours(date.getHours() + offset.h);
                        $(this).text(date.getFullYear() + '-' + add0(date.getMonth() + 1) + '-' + add0(date.getDate()));
                        if ($(this).is('.finish-date') && date.getTime() < Date.now()) {
                            $(this).parents('tr').find('.offer_status_control .b-activate').addClass('expired');
                        }
                    } else {
                        $(this).html('&#8734;');
                    }
                });
                disableButtonActivate();
            });
            function getTime(time, offset){
                let h = +time.substr(0, 2) + offset.h;
                let m = +time.substr(3, 2) + offset.m;
                if (m > 59) { m -=60; h++; }
                if (m < 0) { m +=60; h--; }
                m = add0(m);
                if (h > 23) h -= 24;
                if (h < 0) h += 24;
                h = add0(h);
                return h + ':' + m;
            }
        }

        function offerStatusControl(){
            $('.offer_status_control form').on('submit', function(e){
                e.preventDefault();

                let $nau_balance = $('#nau_balance');
                let $box = $(this).parents('.offer_status_control');
                let $tr = $box.parents('tr');
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
                balanceFineChanging();

                $.ajax({
                    method: "PUT",
                    url: $(this).attr('action'),
                    headers: { 'Accept':'application/json' },
                    data: formData,
                    success: function(data, textStatus, xhr){
                        if (202 === xhr.status){
                            let status = $offer_status.val();
                            let $offerStatusText = $box.parent().children('.offer-status').find('.offer-status-text');
                            $offerStatusText.text(status);
                            $tr.get(0).dataset.offerStatus = status;
                            $offerStatusText[(status === 'active' ? 'remove' : 'add') + 'Class']('offer-status-text-color-deactive')[(status === 'active' ? 'add' : 'remove') + 'Class']('offer-status-text-color-active');
                            $box.removeClass('osc_wait').addClass('osc_' + status);

                            let $details = $box.prev('.details-code');
                            let $next = $box.parent().next().children(':first');
                            if ($next.attr('colspan')) $details = $details.add($next);
                            let $btnWrap = $details.find('.offer-edit-button-wrapper');
                            if (status === 'active') $btnWrap.addClass('oeb-active').removeClass('oeb-deactive');
                            else $btnWrap.addClass('oeb-deactive').removeClass('oeb-active');
                            $offer_status.val(status === 'active' ? 'deactive' : 'active');
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
                        if (401 === resp.status) UnAuthorized();
                        else if (0 === resp.status) AdBlockNotification();
                        else {
                            setNauBalance($nau_balance, -deltaNau);
                            disableButtonActivate();
                            balanceFineChanging();
                            $err.text('err-st: ' + resp.status);
                            console.dir(resp);
                            alert(`Error ${resp.status}: ${resp.responseText}`);
                        }
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
                let nau = parseFloat($nau.attr('data-balance')) * 10000;
                let nau_text = parseFloat($nau.text()) * 10000;
                let delta = (nau - nau_text) / duration * frame;
                let timerID = $nau.attr('data-timerid');
                if (timerID) clearInterval(parseInt(timerID));
                $nau.attr('data-timerid', setInterval(function(){
                    let text = (nau_text += delta) / 10000 + '';
                    let dot = text.indexOf('.');
                    if (dot > -1) text = text.substr(0, dot) + text.substr(dot, 5);
                    $naus.text(text);
                    if (Date.now() > start_time + duration) {
                        clearInterval(parseInt($nau.attr('data-timerid')));
                        let text = nau / 10000 + '';
                        let dot = text.indexOf('.');
                        if (dot > -1) text = text.substr(0, dot) + text.substr(dot, 5);
                        $naus.text(text);
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
                let tr = btn.parentElement;
                while (tr && tr.tagName.toLowerCase() !== 'tr') tr = tr.parentElement;
                let noImage = tr && tr.classList.contains('offer-status-no-image');
                let offerDeactive = tr && tr.dataset.offerStatus === 'deactive';
                let expired = btn.classList.contains('expired');
                tr.classList[(offerDeactive && reserved > nau) ? 'add' : 'remove']('offer-status-no-nau');
                tr.classList[expired ? 'add' : 'remove']('offer-status-expired');
                btn.disabled = expired || reserved > nau || noImage;
            });
        }

        function btnDeleteOffer(){
            document.querySelector('#table_your_offers').addEventListener('click', function(e){
               if (e.target.classList.contains('offer-delete-button')) {
                   let url = e.target.dataset.action;
                   let xhr = new XMLHttpRequest();
                   xhr.onreadystatechange = function() {
                       if (xhr.readyState === XMLHttpRequest.DONE) {
                           if (xhr.status === 401) UnAuthorized();
                           else if (xhr.status === 0) AdBlockNotification();
                           else if (xhr.status === 204) {
                               alert('Offer was deleted.');
                               location.reload();
                           } else if (xhr.status === 404) {
                               alert('Offer not found.');
                           } else if (xhr.status === 422) {
                               alert(xhr.responseText);
                           } else {
                               alert('Something wrong, error ' + xhr.status + ' (see console).');
                           }
                       }
                   };
                   xhr.open('POST', url, true);
                   let data = new FormData();
                   data.append('_token', '{{ csrf_token() }}');
                   data.append('_method', 'DELETE');
                   xhr.send(data);
               }
            });
        }

    </script>
@endpush

@stop
