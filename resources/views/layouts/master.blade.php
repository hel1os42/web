<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no"/>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <link href=" {{ asset('jquery/jquery-ui-1.9.2.custom.css') }} " rel="stylesheet" type="text/css">
    <link href=" {{ asset('jquery/jquery.timepicker.css') }} " rel="stylesheet" type="text/css">
    <link href=" {{ asset('css/base.css') }} " rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
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
</div>


<!-- jQuery picker -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src=" {{ asset('jquery/jquery-1.8.3.js')            }} "></script>
    <script src=" {{ asset('jquery/jquery-ui-1.9.2.custom.js')  }} "></script>
    <script src=" {{ asset('jquery/jquery.timepicker.js')       }} "></script>
    <script src=" {{ asset('jquery/moment.min.js')       }} "></script>


<!-- Location picker -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ81-fUpHTJ73LOtZLzZjGjkUWl0TtvWA&libraries=places"></script>
    <script src=" {{ asset('jquery/locationpicker.jquery.js')  }} "></script>


<!-- Main JS -->
    <script src=" {{ asset('js/main.js')                    }} "></script>
</body>
</html>

