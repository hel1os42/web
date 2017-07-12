{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

{{$user->name}}<br>
{{$user->email}}<br>