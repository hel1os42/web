@extends('layouts.master')

@section('title', 'List offers')

@section('content')
    <div class="offer">
        @foreach ($data as $offer)
            <form method="POST" action="{{route('offer.picture.store', ['offerId' => $offer['id']])}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="file" name="picture">
                <button type="submit">Set photo</button>
            </form>
            <hr/>
            <img src="{{$offer['picture_url']}}" style="float:left;" />
            <ul style="font-size: small">
            @foreach ($offer as $val)
                <li>{{$val}}</li>
            @endforeach
            </ul>
            <hr/>
        @endforeach
    </div>
@stop
