@extends('layouts.master')

@section('title', 'Show offer')

@section('content')
    <div class="offer">
            <pre>
                {{$label}}
                {{$description}}
                {{$reward}}
                {{$status}}
                {{$start_date}} / {{$start_time}}
                {{$finish_date}} / {{$finish_time}}
                {{$category_id}}
                {{$max_count}}
                {{$max_for_user}}
                {{$max_per_day}}
                {{$max_for_user_per_day}}
                {{$user_level_min}}

                geo data:
                {{$country}}
                {{$city}}
                {{$latitude}}
                {{$longitude}}
                {{$radius}}
            </pre>
    </div>
@stop