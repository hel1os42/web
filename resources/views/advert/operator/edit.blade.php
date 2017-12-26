@extends('layouts.master')

@section('title', 'Edit operator')

@section('content')
	@if(!empty($errors->first()))
		<div class="row col-lg-12">
			<div class="alert alert-danger">
				<span>{{ $errors->first() }}</span>
			</div>
		</div>
	@endif
	<h1>Edit Operator</h1>
	<div class="">
		<form action="{{route('advert.operators.update', $id)}}" method="POST">
			{{ method_field('PUT') }}
			{{ csrf_field() }}
            <input name="id" type="hidden" value="{{ $id }}">
			<input name="place_uuid" type="hidden" value="{{ $place_uuid }}">
			<input name="login" placeholder="login" value="{{ $login }}"><br>
			<input type="password" name="password" placeholder="password" value=""><br>
			<input type="password" name="confirm" placeholder="confirm" value=""><br>
			<input title="" name="is_active" type="radio" value='1'>active
			<input title="" name="is_active" type="radio" value='0' checked>deactive<br><br>
			<input type="submit">
		</form>
	</div>

@stop
