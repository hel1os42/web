{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

<p>The following data was successfully saved!</p> <br>

sender acc id - {{ $source_account_id  }} <br>
destination acc id - {{ $destination_account_id }} <br>
amount - {{ $amount }}