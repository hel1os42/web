@extends('layouts.master')

@section('title', 'List offers')

@section('content')
@php
    foreach ($data as &$offer) {
        $offer = array_merge(['picture_url' => $offer['picture_url']], $offer);
    }
@endphp
<div class="col-md-12">
    <h3 class="title">Offers</h3>
    <div class="card card-very-long">
        <div class="content">
            <div class="table-responsive card-very-long-children">
                <table class="table table-hover">
                    <thead class="text-primary">
						<tr>
                            @foreach (array_keys($data[0]) as $offerField)
                                    @if ($offerField != 'id')
                                        @if ($offerField == 'picture_url')
    			    				        <th> picture </th>
                                        @else
    			    				        <th> {{ $offerField }} </th>
                                        @endif
                                    @endif
                            @endforeach
						</tr>
                        
                    </thead>
                    <tbody>

                        @foreach ($data as $offer)
                        <tr class="clickable-table-row" data-uuid="{{route('advert.offers.show', $offer['id'])}}">
                            @foreach($offer as $key => $row)
                                @if (empty($row))
                                    <td> - </td>
                                @else
                                    @if ($key != 'id')
                                    <td> 
                                            @if ($key === 'picture_url')
                                                    <img id="img-{{$offer['id']}}" src="{{ $row }}" alt="...">
                                            @else
                                                {{ $row }} 
                                            @endif
                                    </td>
                                    @endif
                                @endif
                            @endforeach
                            <td> @include('partials/offer-picture-filepicker', ['offerId' => $offer['id']]) </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
