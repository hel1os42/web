@extends('layouts.master')

@section('title', 'Search offer')

@section('content')
    <div class="title">Search offer</div>
    <div class="offer">
        <form action="{{route('offers.index')}}" target="_top">
            {{ csrf_field() }}
            <select id="offer-category" name="category">
            </select><br>
            <input type="text" name="latitude" placeholder="latitude" value="{{request('latitude')}}"><br>
            <input type="text" name="longitude" placeholder="longitude" value="{{request('longitude')}}"><br>
            <input type="text" name="radius" placeholder="radius" value="{{request('radius')}}"><br>
            @if(request('latitude'))
                <iframe width="400" height="250"
                        src="https://maps.google.com/maps?q={{request('latitude')}},{{request('longitude')}}&hl=en&output=embed"></iframe>
                <br>
            @endif
            <input type="submit">
        </form>
        @if(isset($data))
            <h2>Results</h2>
            @foreach($data as $offer)
                <a href="{{route('offers.show', $offer['id'])}}">{{$offer['label']}}</a><br>
            @endforeach
        @endif
    </div>
    <script type="text/javascript">
        function loadCategory(){
            let xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === XMLHttpRequest.DONE) {
                    if (xmlhttp.status === 401) UnAuthorized();
                    else if (xmlhttp.status === 200) {
                        let sel = document.getElementById("offer-category");
                        sel.innerHTML = xmlhttp.responseText;
                        for (let opt, j = 0; opt = sel.options[j]; j++) {
                            if (opt.value === "{{request('category')}}") {
                                sel.selectedIndex = j;
                                break;
                            }
                        }
                    }
                    else if (xmlhttp.status === 400) {
                        alert('There was an error 400');
                    }
                    else {
                        alert('something else other than 200 was returned');
                    }
                }
            };

            xmlhttp.open("GET", "{{route('categories')}}", true);
            xmlhttp.send();
        }

        if(loadCategory()){

        }



    </script>
@stop
