@extends('layouts.master')

@section('title', 'Create user')
@section('content')
    <div class="col-md-9" style="margin-left:200px; margin-top: 50px">
    @if (isset($errors))
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif
    <form action="{{route('register')}}" method="POST"
          enctype="application/x-www-form-urlencoded">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-6 p-5">
                <p><strong>Email</strong></p>
                <p><strong>Password</strong></p>
                <p><strong>Password confirm</strong></p>
                <p><strong>Phone</strong></p>
            </div>
            <div class="col-sm-6 p-10 p-5">
                <p><input style="line-height: 14px; font-size: 14px;" type="text" name="email"
                          value=""></p>
                <p><input type="password" name="password" placeholder="password"></p>
                <p><input type="password" name="password_confirm" placeholder="password_confirmation"></p>
                <p><input style="line-height: 14px; font-size: 14px;" type="text" name="phone"
                          value=""></p>
            </div>
            <button type="submit">Create</button>
        </div>
    </form>
    </div>
@stop
