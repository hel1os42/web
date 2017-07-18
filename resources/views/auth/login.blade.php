{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

<form action="{{route('login')}}" method="post" target="_top">
    {{ csrf_field() }}
    
    <input type="email" name="email" placeholder="email">
    <input type="password" name="password" placeholder="password">
    <input type="submit">
</form>