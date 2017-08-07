{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

<p>The following data was successfully saved!</p> <br>

sender - {{ $sender->getOwnerId() }} <br>
destination - {{ $destination->getOwnerId() }} <br>
amount - {{ $amount }}