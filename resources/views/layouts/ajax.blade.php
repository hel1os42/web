@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

@yield('content')