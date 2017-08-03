{!! session()->has('message') ? '<p>'.session()->get('message').'</p>' : '' !!}

        <!DOCTYPE html>
<html>
<head>
    <title>Search offer</title>

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
            <form action="{{route('offers.index')}}" target="_top">
                {{ csrf_field() }}
                <select id="offer-category" name="category">
                </select><br>
                <input name="latitude" placeholder="latitude" value=""><br>
                <input name="longitude" placeholder="longitude" value=""><br>
                <input name="radius" placeholder="radius" value=""><br>
                <input type="submit">
            </form>
            @if($data['results'])
                <h2>Results</h2>
                @foreach($data['results'] as $offer)
                    <a href="{{route('offer.show', $offer->id)}}">{{$offer->name}}</a><br>
                    @endforeach
                @endif
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

    xmlhttp.open("GET", "{{route('category')}}", true);
    xmlhttp.send();
</script>
</body>
</html>
