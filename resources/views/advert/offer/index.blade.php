@extends('layouts.master')

@section('title', 'List offers')

@section('content')

<div class="col-md-12">
    <h3 class="title">Offers</h3>
    <div class="card card-very-long">
        <div class="content">
            <div class="table-responsive card-very-long-children">
                <table class="table">
                    <thead class="text-primary">
						<tr>
                            @foreach (array_keys($data[0]) as $offerField)
							    <th> {{ $offerField }} </th>
                            @endforeach
						</tr>
                        
                    </thead>
                    <tbody>

                        @foreach ($data as $offer)
                        <tr>
                            @foreach($offer as $key => $row)
                                @if (empty($row))
                                    <td> - </td>
                                @else
                                    <td> 
                                        @if ($key == 'id')
                                            <a href="{{route('advert.offers.show', $row)}}">{{ $row }} </a>
                                        @else
                                            {{ $row }} 
                                        @endif
                                    </td>
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
