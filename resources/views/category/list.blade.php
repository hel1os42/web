@extends('layouts.ajax')
@section('content')
    @foreach($data as $category)
        <option value="{{$category['id']}}">{{$category['name']}}</option>
    @endforeach
@stop