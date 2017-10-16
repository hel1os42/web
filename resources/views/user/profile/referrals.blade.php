@extends('layouts.master')

@section('title', 'Profile')

@section('content')
    <div class="col-md-12">
        <h3>Referrals</h3>
        <div class="card card-very-long">
            <div class="content">
                <div class="table-responsive card-very-long-children">
                    <table class="table">
                        <thead class="text-primary">
			    			<tr>
                                @foreach (array_keys($data[0]) as $referralField)
			    				    <th> {{ $referralField }} </th>
                                @endforeach
			    			</tr>
                            
                        </thead>
                        <tbody>

                            @foreach ($data as $referral)
                            <tr>
                                @foreach($referral as $key => $row)
                                    @if (empty($row))
                                        <td> - </td>
                                    @else
                                        <td> {{ $row }} </td>
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
