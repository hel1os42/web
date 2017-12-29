@extends('layouts.master')

@section('title', 'List offer')

@section('content')
    @if (isset($data))
        <p>
        <table style="width: 100%">
            <tr>
                <td style="padding-left: 10px">
                    amount
                </td>
                <td style="padding-left: 10px">
                    status
                </td>
                <td style="padding-left: 10px">
                    created_at
                </td>
                <td style="padding-left: 10px">
                    updated_at
                </td>
                <td style="padding-left: 10px">
                    type
                </td>
                <td style="padding-left: 10px">
                    source_account_id
                </td>
                <td style="padding-left: 10px">
                    destination_account_id
                </td>
            </tr>
            @foreach ($data as $transaction)
                <tr>
                    <td style="padding-left: 10px">
                        <a href="{{route('transactionList', $transaction['id'])}}">{{ $transaction['amount'] }}</a>
                    </td>
                    <td style="padding-left: 10px">
                        {{ $transaction['status'] }}
                    </td>
                    <td style="padding-left: 10px">
                        {{ $transaction['created_at'] }}
                    </td>
                    <td style="padding-left: 10px">
                        {{ $transaction['updated_at'] }}
                    </td>
                    <td style="padding-left: 10px">
                        {{ $transaction['type'] }}
                    </td>
                    <td style="padding-left: 10px">
                        {{ $transaction['source_account_id'] }}
                    </td>
                    <td style="padding-left: 10px">
                        {{ $transaction['destination_account_id'] }}
                    </td>
                </tr>
            @endforeach
        </table>
        </p>
        @include('pagination.default', compact('current_page','from','last_page','next_page_url','path','per_page','prev_page_url','to','total'))
    @endif

@stop