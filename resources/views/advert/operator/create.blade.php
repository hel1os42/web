@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')

	<h1>Create Operator</h1>
	<div class="">
		<form action="{{route('advert.operators.store')}}" method="post" target="_top">
			{{ csrf_field() }}
			<input name="login" placeholder="login" value="{{old('login')}}"> <br>
			<input name="password" placeholder="password"><br>
			<input name="confirm" placeholder="confirm"><br><br>
			<input type="submit">
		</form>
	</div>

@stop
