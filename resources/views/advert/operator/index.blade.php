@extends('layouts.master')

@section('title', 'List operators')

@section('content')
<div class="col-md-12">
    <h3 class="title">Operators</h3>
    <div class="card card-very-long">
        <div class="content">
            <div class="table-responsive card-very-long-children">
                <table class="table table-hover">
                    @if(0 !== count($data))
                        <thead class="text-primary">
                            <tr>
                                @foreach (array_keys($data[0]) as $field)
                                    <th> {{ $field }} </th>
                                @endforeach
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($data as $operator)
                            <tr class="clickable-table-row" data-uuid="">
                                @foreach($operator as $key => $field)
                                    <td>
                                    @if('is_active' === $key)
                                        @if(true === $field)
                                            Active
                                        @else
                                            Deactive
                                        @endif
                                    @else
                                        @if (empty($field))
                                            <td> - </td>
                                        @else
                                            {{ $field }}
                                        @endif
                                    @endif
                                @endforeach
                                <td>
                                    <form method="post" action="{{ route('advert.operators.destroy', $operator['id']) }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-rose btn-wd btn-md">Delete</button></form>
                                </td>
                                <td>
                                    <form method="get" action="{{ route('advert.operators.edit', $operator['id']) }}">
                                        <button type="submit" class="btn btn-rose btn-wd btn-md">Edit</button>
                                    </form>
                                </td>
                                <td>
                                    @if($operator['is_active'] === false)
                                        <form action="{{route('advert.operators.changeActive', $operator['id'])}}" method="post" style="">
                                            <input type="hidden" name="_method" value="PUT">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="is_active" value="1">
                                            <button type="submit" class="btn btn-wd btn-md">activate</button>
                                        </form>
                                    @else
                                        <form action="{{route('advert.operators.changeActive', $operator['id'])}}" method="post" style="">
                                            <input type="hidden" name="_method" value="PUT">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="is_active" value="0">
                                            <button type="submit" class="btn btn-wd btn-md">deactivate</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
                <form method="get" action="{{ route('advert.operators.create') }}">
                    <button type="submit" class="btn btn-rose btn-wd btn-md">or create new</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
