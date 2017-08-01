{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

        <!DOCTYPE html>
<html>
<head>
    <title>Create offer</title>

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
            padding: 2%;
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
            margin-top: 100px;
        }
    </style>
</head>
<body>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">
    <div class="content">
        <div class="header">
            <div class="header-right"><a href="{{route('logout')}}">Logout</a></div>
        </div>
        <div class="offer">
            <form action="{{route('advert.offer')}}" method="post" target="_top">
                {{ csrf_field() }}
                <input name="label" placeholder="label" value="{{$data->label}}"> <br>
                <input name="description" placeholder="description"
                       value="{{$data->description}}"><br>
                <input name="reward" placeholder="reward" value="{{$data->reward}}"><br>
                <input name="start_date" placeholder="start_date" value="{{$data->date_start}}">
                <input name="start_time" placeholder="start_time" value="{{$data->time_start}}"><br>
                <input name="finish_date" placeholder="finish_date" value="{{$data->date_finish}}">
                <input name="finish_time" placeholder="finish_time"
                       value="{{$data->time_finish}}"><br>
                <select id="offer-category" name="category">
                </select><br>
                <input name="max_count" placeholder="max_count" value="{{$data->max_count}}"><br>
                <input name="max_for_user" placeholder="max_for_user" value="{{$data->max_for_user}}"><br>
                <input name="max_per_day" placeholder="max_per_day" value="{{$data->max_per_day}}"><br>
                <input name="max_for_user_per_day" placeholder="max_for_user_per_day" value="{{$data->max_for_user_per_day}}"><br>
                <input name="user_level_min" placeholder="user_level_min" value="{{$data->min_level}}"><br>
                <input name="latitude" placeholder="latitude" value="{{$data->latitude}}"><br>
                <input name="longitude" placeholder="longitude" value="{{$data->longitude}}"><br>
                <input name="radius" placeholder="radius" value="{{$data->radius}}"><br>
                <input name="country" placeholder="country" value="{{$data->country}}"><br>
                <input  name="city" placeholder="city" value="{{$data->city}}"><br>
                <input type="submit">
            </form>
        </div>
        <div class="title">NAU</div>
    </div>
</div>
<script type="text/javascript">
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == XMLHttpRequest.DONE ) {
                if (xmlhttp.status == 200) {
                    document.getElementById("offer-category").innerHTML = xmlhttp.responseText;
                }
                else if (xmlhttp.status == 400) {
                    alert('There was an error 400');
                }
                else {
                    alert('something else other than 200 was returned');
                }
            }
        };

        xmlhttp.open("GET", "{{route('offer.category')}}", true);
        xmlhttp.send();
</script>

</body>
</html>
