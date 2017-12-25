@extends('layouts.master')

@section('title', 'Show operator detail\'s')

@section('content')

    <div class="col-md-6">
        <div class="card">
            <div class="row">
                <div class="content">
                    <div class="col-sm-6 p-5">
                            @foreach($data as $field => $value)
                                <p><strong>{{ $field }}</strong></p>
                                @if('is_active' === $field)
                                    @if(true === $value)
                                        <p>Active</p>
                                    @else
                                        <p>Deactive</p>
                                    @endif
                                @else
                                    <p>{{ $value }}</p>
                                @endif
                            @endforeach
                    </div>
                </div>
            </div>
            <form method="post" action="{{ route('advert.operators.destroy', $data['id']) }}">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-rose btn-wd btn-md">Delete operator</button>
            </form>
            <form method="get" action="{{ route('advert.operators.edit', $data['id']) }}">
                <button type="submit" class="btn btn-rose btn-wd btn-md">Edit operator</button>
            </form>
        </div>
    </div>
@stop
