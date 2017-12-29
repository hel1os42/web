@extends('layouts.master')

@section('title', 'Reset password')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Reset Password</div>
                    <div class="panel-body">
                        @if (isset($message))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
