{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

<!DOCTYPE html>
<html>
<head>
    <title>NAU</title>

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
            position: fixed;
            top: 0;
            height: 40px;
            border-bottom: 1px solid #38bdff;
            width: 96%;
            left: 0;
            padding:2%;
        }

        .header-right {
            float: right;
            margin-right: 20px;
            color: black;
            font-size: 18px;
            font-weight: bold;
        }

        .offer {
            color: black;
            font-size: 25px;
            text-align: left;
            font-weight: bold;
            margin-bottom: 100px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="header">
            <div class="header-right"> <a href="{{route('logout')}}">Logout</a></div>
        </div>
        <div class="offer">
            <form action="{{route('offer')}}" method="post" target="_top">
                {{ csrf_field() }}
                <input type="name" name="name" placeholder="name" value="{{$name}}"> <br>
                <input type="description" name="description" placeholder="description" value="{{$description}}"><br>
                <input type="reward" name="reward" placeholder="reward" value="{{$reward}}"><br>
                <input type="date_start" name="date_start" placeholder="date_start" value="{{$date_start}}">
                <input type="time_start" name="time_start" placeholder="time_start" value="{{$time_start}}"><br>
                <input type="date_finish" name="date_finish" placeholder="date_finish" value="{{$date_finish}}">
                <input type="time_finish" name="time_finish" placeholder="time_finish" value="{{$time_finish}}"><br>
                <input type="category" name="category" placeholder="category" value="{{$category}}"><br>
                <input type="max_count" name="max_count" placeholder="max_count" value="{{$max_count}}"><br>
                <input type="max_for_user" name="max_for_user" placeholder="max_for_user" value="{{$max_for_user}}"><br>
                <input type="max_per_day" name="max_per_day" placeholder="max_per_day" value="{{$max_per_day}}"><br>
                <input type="max_for_user_per_day" name="max_for_user_per_day" placeholder="max_for_user_per_day" value="{{$max_for_user_per_day}}"><br>
                <input type="min_level" name="min_level" placeholder="min_level" value="{{$min_level}}"><br>
                <input type="latitude" name="latitude" placeholder="latitude" value="{{$latitude}}"><br>
                <input type="longitude" name="longitude" placeholder="longitude" value="{{$longitude}}"><br>
                <input type="radius" name="radius" placeholder="radius" value="{{$radius}}"><br>
                <input type="country" name="country" placeholder="country" value="{{$country}}"><br>
                <input type="city" name="city" placeholder="city" value="{{$city}}"><br>
                <input type="submit">
            </form>
        </div>
        <div class="title">NAU</div>
    </div>
</div>
</body>
</html>
