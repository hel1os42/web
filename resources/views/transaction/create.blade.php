@extends('layouts.master')

@section('title', 'Create transaction')

@section('content')
    <div class="col-md-8">
        <div class="content">
            <h3 class="title">Create transaction</h3>
            <div class="card">
                <div class="content">
                    <form class="form-horizontal" action="{{ route('transaction.complete') }}" method="post">
                        {{ csrf_field() }}

                        <fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Source</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="source" value="{{ $source }}">
                                    @foreach($errors->get('source') as $message)
                                        <p class="text-danger">
                                            {{$message}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Destination</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="destination" value="{{ old('destination') }}">
                                    @foreach($errors->get('destination') as $message)
                                        <p class="text-danger">
                                            {{$message}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Amount</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="amount" value="{{ old('amount') }}">
                                    @foreach($errors->get('amount') as $message)
                                        <p class="text-danger">
                                            {{$message}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </fieldset>
                        <input class="btn btn-rose btn-wd btn-md" type="submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
