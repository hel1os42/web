{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}
{{$id}}<br>
{{$name}}<br>
{{$email}}<br>
<br>
<a href="/auth/logout">Logout</a>