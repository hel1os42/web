@extends('layouts.master')

@section('title', 'Create redemption')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div style="margin-top: 20px">
                    <form action="{{route('redemptions.store')}}" method="post" class="nau-form" target="_top">
                        {{ csrf_field() }}
                        <input name="code" placeholder="code" value=""><br>

                        <input type="submit" class="btn">
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
