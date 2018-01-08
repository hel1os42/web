@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')
	@if(!empty($errors->first()))
		<div class="row col-lg-12">
			<div class="alert alert-danger">
				<span>{{ $errors->first() }}</span>
			</div>
		</div>
	@endif
	<h1>Create Operator</h1>
	<div class="">
		<form action="{{route('advert.operators.store')}}" method="post" target="_top">
			{{ csrf_field() }}
			<p>You'r ID place - {{$place_uuid}}</p>
			<input name="place_uuid" type="hidden" value="{{$place_uuid}}">
			<input name="login" placeholder="login" value="{{old('login')}}"><br>
			<input name="password" type="password" placeholder="password" value=""><br>
			<input name="confirm" type="password" placeholder="confirm" value=""><br>
			<input title="" name="is_active" type="radio" value='1'>active
			<input title="" name="is_active" type="radio" value='0' checked>deactive<br><br>
			<input type="submit">
		</form>
	</div>

@stop
