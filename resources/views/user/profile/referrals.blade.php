@extends('layouts.master')

@section('title', 'Profile')
@php
    foreach ($data as &$offer) {
        $offer = array_merge(['picture_url' => $offer['picture_url']], $offer);
    }
@endphp
@section('content')
    <div class="col-md-12">
        <h3>Referrals</h3>
        <div class="card card-very-long">
            <div class="content">
                <div class="table-responsive card-very-long-children">
                    <table class="table table-hover">
                        <thead class="text-primary">
			    			<tr>
                                @foreach (array_keys($data[0]) as $referralField)
                                    @if ($referralField != 'id')
                                        @if ($referralField == 'picture_url')
    			    				        <th> picture </th>
                                        @else
    			    				        <th> {{ $referralField }} </th>
                                        @endif
                                    @endif
                                @endforeach
			    			</tr>
                            
                        </thead>
                        <tbody>

                            @foreach ($data as $referral)
                            <tr class="clickable-table-row" data-uuid="{{route('users.show', $referral['id'])}}">
                                @foreach($referral as $key => $row)
                                    @if (empty($row))
                                        <td> - </td>
                                    @else
                                        @if ($key != 'id')
                                            @if ($key === 'picture_url')
                                                <td> 
                                                    <img src="{{ $row }}" alt="">
                                                </td>
                                            @else
                                                <td> {{ $row }} </td>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
