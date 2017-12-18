@extends('layouts.master')

@section('title', 'List operators')

@section('content')
{{ print_r($data[0]) }}
<div class="col-md-12">
    <h3 class="title">Operators</h3>
    <div class="card card-very-long">
        <div class="content">
            <div class="table-responsive card-very-long-children">
                <table class="table table-hover">
                    <thead class="text-primary">
						<tr>
                            {{--@foreach ($data as $operator)--}}
                                {{--@foreach($operator as $field)--}}
                                    {{--@foreach(array_keys($field) as $name)--}}
    			    			        {{--<th> {{ $name }} </th>--}}
                                    {{--@endforeach--}}
                                {{--@endforeach--}}
                            {{--@endforeach--}}
						</tr>
                        
                    </thead>
                    <tbody>

                        @foreach ($data as $operator)
                        <tr class="clickable-table-row">
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
                </table>
            </div>
        </div>
    </div>
</div>
@stop
