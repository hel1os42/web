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
            <form action="{{route('advert.offers.store')}}" method="post" target="_top">
                {{ csrf_field() }}
                <input name="label" placeholder="label" value="{{$label}}"> <br>
                <input name="description" placeholder="description"
                       value="{{$description}}"><br>
                <input name="reward" placeholder="reward" value="{{$reward}}"><br>
                <input name="start_date" placeholder="start_date" value="{{$start_date}}"><br>

                <input name="finish_date" placeholder="finish_date" value="{{$finish_date}}"><br>
                <input name="start_time" placeholder="start_time" value="{{$start_time}}"><br>
                <input name="finish_time" placeholder="finish_time" value="{{$finish_time}}"><br>
                <select id="offer-category" name="category_id">
                </select><br>
                <input name="max_count" placeholder="max_count" value="{{$max_count}}"><br>
                <input name="max_for_user" placeholder="max_for_user" value="{{$max_for_user}}"><br>
                <input name="max_per_day" placeholder="max_per_day" value="{{$max_per_day}}"><br>
                <input name="max_for_user_per_day" placeholder="max_for_user_per_day" value="{{$max_for_user_per_day}}"><br>
                <input name="user_level_min" placeholder="user_level_min" value="{{$user_level_min}}"><br>
                <input name="latitude" placeholder="latitude" value="{{$latitude}}"><br>
                <input name="longitude" placeholder="longitude" value="{{$longitude}}"><br>
                <input name="radius" placeholder="radius" value="{{$radius}}"><br>
                <input name="country" placeholder="country" value="{{$country}}"><br>
                <input name="city" placeholder="city" value="{{$city}}"><br>
                <input type="submit">
            </form>
        </div>
        <div class="title">NAU</div>
    </div>
</div>
<script type="text/javascript">
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {
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

    xmlhttp.open("GET", "{{route('categories')}}", true);
    xmlhttp.send();
</script>

</body>
</html>
