{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

@if (isset ($source_account_id))
    sender acc id - {{ $source_account_id  }} <br>
@endif

@if (isset ($destination_account_id))
    destination acc id - {{ $destination_account_id }} <br>
@endif

@if (isset ($amount))
    amount - {{ $amount }}
@endif