@extends('layouts.master')

@section('title', 'Edit operator')

@section('content')

	<h1>Edit Operator</h1>
	<div class="">
		<form action="{{route('advert.operators.update', $data['id'])}}" method="POST">
			{{ method_field('PUT') }}
			{{ csrf_field() }}
            <input name="id" type="hidden" value="{{ $data['id'] }}">
			<input name="place_uuid" type="hidden" value="{{ $data['place_uuid'] }}">
			<input name="login" placeholder="login" value="{{ $data['login'] }}"><br>
			<input type="password" name="password" placeholder="password" value=""><br>
			<input type="password" name="confirm" placeholder="confirm" value=""><br><br>
			<input type="submit">
		</form>
	</div>

@stop
