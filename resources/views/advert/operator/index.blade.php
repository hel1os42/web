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
                            <tr class="clickable-table-row" data-uuid="{{route('advert.operators.show', $operator['id'])}}">
                                @foreach($operator as $field)
                                    @if (empty($field))
                                        <td> - </td>
                                    @else
                                        <td>
                                            {{ $field }}
                                        </td>
                                    @endif
                                @endforeach
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@stop
