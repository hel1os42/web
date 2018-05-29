@extends('layouts.master')
@section('title', 'Settings')
@section('content')
    <div class="container">
    <nav class="row">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">item</th>
                    <th scope="col">value</th>
                    </tr>
                </thead>
                <tbody>
                <form id="form" action="{{ route('settings.apply') }}" method="POST">
                    {{ csrf_field() }}
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{$key}}</td>
                        <td><input name="{{$key}}" value="{{$value}}"></td>
                        </tr>
                    @endforeach
                    </form>
                </tbody>
                </table>
            <button form="form" type="submit" value="Submit" class="btn">apply changes</button>
            </div>
        </nav>
    </div>
@stop
