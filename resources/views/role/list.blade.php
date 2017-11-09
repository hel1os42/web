@extends('layouts.ajax')
@section('content')
    @foreach($roles as $role)
        <option value="{{$role['id']}}">{{$role['name']}}</option>
    @endforeach
@stop