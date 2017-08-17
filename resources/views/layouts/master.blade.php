<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #38bdff;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
            padding-top: calc(40px + 4%);
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }

        .header {
            background-color: rgb(230, 230, 230);
            position: fixed;
            top: 0;
            height: 60px;
            border-bottom: 1px solid #38bdff;
            width: 98%;
            left: 0;
            padding: 1%;
        }

        .header-right {
            margin-top: 15px;
            float: right;
            margin-right: 20px;
            color: black;
            font-size: 18px;
            font-weight: bold;
        }

        .header-logo {
            float: left;
            font-size: 50px;
        }

        .offer {
            color: black;
            font-size: 25px;
            text-align: left;
            font-weight: bold;
            margin-bottom: 100px;
            margin-top: 100px;
        }

        .profile {
            color: black;
            font-size: 25px;
            text-align: left;
            font-weight: bold;
            margin-bottom: 100px;
        }

        .alert.alert-danger {
            text-align: left;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    @section('header')
        <div class="header">
            <div class="header-logo">NAU</div>
            <div class="header-right">
                @if (\Auth::check())
                    <a href="{{route('profile.show')}}">Profile</a> | <a href="{{route('logout')}}">Logout</a>
                @else
                    Hello guest! &nbsp; <a href="{{route('loginForm')}}">login</a>
                @endif
            </div>
        </div>
    @show

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

    <div class="content">
        @yield('content')
    </div>
</div>
</body>
</html>