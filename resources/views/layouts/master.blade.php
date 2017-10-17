@include('partials.head')
<body>
<!--div class="container">
    @section('header')
        <div class="header">
            <div class="header-logo">NAU</div>
            <div class="header-right">
                @if (\Auth::check())
                    <a href="{{route('profile')}}">Profile</a> | <a href="{{route('logout')}}">Logout</a>
                @else
                    Hello guest! &nbsp; <a href="{{route('loginForm')}}">login</a>
                @endif
            </div>
        </div>
    @show
    @if (isset($errors))
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    {!! session()->has('message') ? '<div class="alert alert-info"><p>'.session()->get('message').'</p></div>' : '' !!}

    <div class="content">
        @yield('content')
    </div>
</div-->

<div class="wrapper perfect-scrollbar-off">
    @section('sidebar')
        @include('partials.sidebar')
    @show
    <div class="main-panel">
        @section('main-panel')
            @include('partials.header')        
        @show
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    @yield('content')
                </div>
            </div> 
        </div>
    </div>
</div>
@include('partials.javascripts')
</body>
</html>
