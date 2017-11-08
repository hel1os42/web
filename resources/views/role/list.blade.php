@extends('layouts.ajax')
@section('content')
    @foreach($data as $role)
        <option value="{{$role['id']}}">{{$role['name']}}</option>
    @endforeach
@stop