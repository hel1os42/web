@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')

	<h1>Create Operator</h1>
	<div class="">
		<form action="{{route('advert.operators.store')}}" method="post" target="_top">
			{{ csrf_field() }}
			<input name="place_uuid" type="hidden" value="{{$place_uuid}}">
			<input name="login" placeholder="login" value="111"><br>
			<input name="password" placeholder="password" value="321"><br>
			<input name="confirm" placeholder="confirm" value="321"><br><br>
			<input type="submit">
		</form>
	</div>

@stop
