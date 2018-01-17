@extends('layouts.master')

@section('title', 'Show operator detail\'s')

@section('content')

    <div class="col-md-6">
        <div class="card">
            <div class="row">
                <div class="content">
                    <div class="col-sm-10 p-5">
                            @foreach(get_defined_vars()['__data'] as $field => $value)

                            @if (!in_array($field, ['app', 'errors', '__env', 'authUser']))
                                <p><strong> {{ $field }} </strong></p>
                                @if('is_active' === $field)
                                    @if(true === $value)
                                        <p>Active</p>
                                    @else
                                        <p>Deactive</p>
                                    @endif
                                @else
                                    <p>{{ $value }}</p>
                                @endif
                            @endif

                            @endforeach
                    </div>
                </div>
            </div>
            <form method="post" action="{{ route('advert.operators.destroy', $id) }}">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-rose btn-wd btn-md">Delete operator</button>
            </form>
            <form method="get" action="{{ route('advert.operators.edit', $id) }}">
                <button type="submit" class="btn btn-rose btn-wd btn-md">Edit operator</button>
            </form>
        </div>
    </div>
@stop
