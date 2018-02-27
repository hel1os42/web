

@extends('layouts.master')

@section('title', 'Offer')

@section('content')

<h1>Offer was created</h1>

{{--@php--}}
    {{--$metaKeys = ['app', 'errors', '__env', 'authUser'];--}}
{{--@endphp--}}
    {{--<div class="col-md-6">--}}
        {{--<div class="card">--}}
            {{--<div class="row">--}}
                {{--<div class="content">--}}
                    {{--<div class="col-sm-6 p-5">--}}
                        {{--@foreach(array_keys(get_defined_vars()['__data']) as $key)--}}
                            {{--@if (!in_array($key, $metaKeys))--}}
                                {{--<p><strong> {{ $key }} </strong></p>--}}
                            {{--@endif--}}
                        {{--@endforeach--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-6 p-10 p-5">--}}
                        {{--@foreach(get_defined_vars()['__data'] as $key => $val)--}}
                            {{--@if (!in_array($key, $metaKeys))--}}
                                {{--@if (!empty($val))--}}
                                    {{--<p> {{ $val }} </p>--}}
                                {{--@else--}}
                                    {{--<p><strong> - </strong></p>--}}
                                {{--@endif--}}
                            {{--@endif--}}
                        {{--@endforeach--}}
                    {{--</div>--}}
                    {{--<form method="post" action="{{ route('advert.offers.destroy', $id) }}">--}}
                        {{--<input type="hidden" name="_method" value="DELETE">--}}
                        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                        {{--<button type="submit" class="btn btn-rose btn-wd btn-md">Delete offer</button>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{----}}
    {{--</div>--}}
    {{--<div class="col-md-4">--}}
        {{----}}
        {{--<div class="card">--}}
            {{--<div class="content">--}}
                {{--<div class="img-container text-center">--}}
                    {{--<img src={{route('offer.picture.show', $id)}}>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="card">--}}
            {{--<div class="content">--}}
                {{--@include('partials/offer-picture-filepicker', ['offerId' => $id])--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@stop
