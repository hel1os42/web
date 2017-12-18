@extends('layouts.master')

@section('title', 'Show operator detail\'s')

@section('content')

    <div class="col-md-6">
        <div class="card">
            <div class="row">
                <div class="content">
                    <div class="col-sm-6 p-5">
                            @foreach($data as $field => $value)
                                <p><strong>{{ $field }}</strong></p> <p>{{ $value }}</p>
                            @endforeach
                    </div>
                </div>
            </div>
            <form method="post" action="{{ route('advert.operators.destroy', $data['id']) }}">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-rose btn-wd btn-md">Delete operator</button>
            </form>
        </div>
    </div>
@stop
