@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')

	<h1>Create Operator</h1>
	<div class="">
		<form action="{{route('advert.operators.store')}}" method="post" target="_top">
			{{ csrf_field() }}
			<p>You'r ID place - {{$place_uuid}}</p>
			<input name="place_uuid" type="hidden" value="{{$place_uuid}}">
			<input name="login" placeholder="login" value="{{old('login')}}"><br>
			<input name="password" type="password" placeholder="password" value=""><br>
			<input name="confirm" type="password" placeholder="confirm" value=""><br><br>
			<input type="submit">
		</form>
	</div>

@stop
