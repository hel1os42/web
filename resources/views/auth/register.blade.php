{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="/user/register" method="post" target="_top">
    {{ csrf_field() }}
    <input type="name" name="name" placeholder="name"> <br>
    <input type="email" name="email" placeholder="email"><br>
    <input type="password" name="password" placeholder="password"><br>
    <input type="password" name="password_confirm" placeholder="password_confirmation"><br>
    <input type="submit">
</form>