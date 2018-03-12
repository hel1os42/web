@extends('layouts.master')

@section('title', 'Create redemption')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div style="margin-top: 20px">
                    <h1>Success</h1>
                    <p class="text-center"><a href="{{ route('redemptions.create') }}" class="btn btn-nau">Redeem next</a></p>
                </div>
            </div>
        </div>
    </div>
@stop