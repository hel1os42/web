@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
@php
    foreach ($data as &$offer) {
        $offer = array_merge(['picture_url' => $offer['picture_url']], $offer);
    }
@endphp

<div class="container">

    <h1>Offers</h1>

    <div class="row">
        <div class="col-xs-12 dashboard-advert-header" style="background-image: url({{ asset('img/advert_img.png') }});">

            <div class="offer-logo"><img src="{{ auth()->user()->picture_url }}" alt="offer name"></div>
            <div class="advert-header-wrap">
                <div class="advert-header">
                    <div class="create-offer"><a href="/advert/offers/create" class="btn-nau btn-create-offer">Create offer</a></div>
                    <div class="advert-info">
                        <p class="advert-name">{{ auth()->user()->name }}</p>
                        <p>{{ auth()->user()->phone }}, {{ auth()->user()->email }}</p>
                    </div>
                    <div class="stat-info clearfix"><!-- not need .row -->
                        <div class="col-xs-4">
                            <span class="icon-offers">Offers:</span>
                            <strong>{{ count($data) }}<!-- TODO: когда будет пагинация - это сломается --></strong>
                        </div>
                        <div class="col-xs-4">
                            <span class="icon-nau">NAU:</span>
                            <strong>{{ auth()->user()->getAccountForNau()->amount }}</strong>
                        </div>
                        <div class="col-xs-4">
                            <span class="icon-statistic">Statistic:</span>
                            <strong>??? 1</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- if (have_childrens_offers) -->

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab_your_offers">Your Offers</a></li>
            <li><a data-toggle="tab" href="#tab_childrens_offers">Children's Offers</a></li>
        </ul>


        <div class="tab-content">

            <div id="tab_your_offers" class="tab-pane fade in active">

                <!-- endif -->

                <!--
                    TODO: когда-нибудь сделать пагинацию и на стороне сервера
                    https://laravel.com/docs/5.5/pagination
                -->
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
                        <th style="display: none;">Details</th>
                    </tr>
                    </tfoot>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($data as $offer)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td class="details-control"><span class="button-details"><img src="{{ $offer['picture_url'] }}" alt="offer picture"></span></td>
                                <td>{{ $offer['label'] }}</td>
                                <td><span data-df="ymd">{{ $offer['start_date'] }}</span> &nbsp;&mdash;&nbsp; <span data-df="ymd">{{ $offer['finish_date'] }}</span></td>
                                <td>{{ $offer['reward'] }}</td>
                                <td>{{ $offer['reserved'] }}</td>
                                <td>{{ $offer['status'] }}</td>
                                <td class="details-code" style="display: none;">
                                    <div>
                                        <div class="row set">
                                            <div class="col-xs-6">
                                                <p class="row"><span class="title col-xs-3">Description:</span> <span class="col-xs-9">{{ $offer['description'] }}</span></p>
                                                <p class="row"><span class="title col-xs-3">Location:</span> <span class="col-xs-9">{{ $offer['country'] }}, {{ $offer['city'] }} (radius: {{ $offer['radius'] / 1000 }} km)<br>{{ $offer['latitude'] }}, {{ $offer['longitude'] }}</span></p>
                                                <p class="row"><span class="title col-xs-3">Category:</span> <span class="col-xs-9" data-fix-category="true">{{ $offer['category_id'] }}</span></p>
                                            </div>
                                            <div class="col-xs-6">
                                                <p class="row"><span class="title col-xs-4">Offer Picture:</span> <span class="col-xs-8"><img id="img-{{ $offer['id'] }}" src="{{ $offer['picture_url'] }}" alt="offer picture" class="offer-picture"></span></p>
                                            </div>
                                        </div>
                                        <div class="row set">
                                            <div class="col-xs-4">
                                                <p class="title">Working time:</p>
                                                <p class="row"><span class="title col-xs-3">mon:</span> <span class="col-xs-9">??? - ???</span></p>
                                                <p class="row"><span class="title col-xs-3">tue:</span> <span class="col-xs-9">??? - ???</span></p>
                                                <p class="row"><span class="title col-xs-3">wed:</span> <span class="col-xs-9">??? - ???</span></p>
                                                <p class="row"><span class="title col-xs-3">thu:</span> <span class="col-xs-9">??? - ???</span></p>
                                                <p class="row"><span class="title col-xs-3">fri:</span> <span class="col-xs-9">??? - ???</span></p>
                                                <p class="row"><span class="title col-xs-3">sat:</span> <span class="col-xs-9">-</span></p>
                                                <p class="row"><span class="title col-xs-3">sun:</span> <span class="col-xs-9">-</span></p>
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
                                                <p class="row"><span class="title col-xs-3">Created at:</span> <span class="col-xs-9" data-df="ymd hms">{{ $offer['created_at'] }}</span></p>
                                                <p class="row"><span class="title col-xs-3">Updated at:</span> <span class="col-xs-9" data-df="ymd hms">{{ $offer['updated_at'] }}</span></p>
                                                <p class="row"><span class="title col-xs-3">Deleted at:</span> <span class="col-xs-9" data-df="ymd hms">{{ $offer['deleted_at'] }}</span></p>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="pull-right">
                                                    &nbsp;<br>
                                                    <button class="btn-nau">Edit information</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            <!-- if (have_childrens_offers) -->
            </div>

            <div id="tab_childrens_offers" class="tab-pane fade">
                <p>Not realised</p>
                <!-- такая же таблица, но + 1 поле: "Advert_name" - реальный owner оффера -->
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/partials/dashboard-advert-header.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('jquery/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('jquery/datatables.fix.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('jquery/datatables.min.js') }}"></script>
    <script>
        window.addEventListener('load', function(){

            dataTableCreate('#table_your_offers');
            dataTableCreate('#table_childrens_offers');

            /* date-time format */
            $('[data-df]').each(function(){
                $(this).text(dateFormat($(this).text(), $(this).data('df')));
            });

            /* TODO: categories names (временный костыль) */
            __getCategoriesNames("{{ route('categories') }}", function(json){
                console.dir(json);
                $('[data-fix-category]').each(function(){
                    $(this).text(json[$(this).text()]);
                });
            });

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
    </script>
@endpush

@stop
