@extends('layouts.master')

@section('title', 'Create category')

@section('content')
    <div class="container">
        <div class="row">
            @include('partials.navbar', [
                'title' => 'Create category(retail type)',
                'leftLinksBar' => [
                    ['link' => route('categories.index'), 'text' => '<i class="fa fa-arrow-left" aria-hidden="true"></i> back to list']
                ]
            ])
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <form action="{{route('categories.store')}}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}">
                    </div>
                    <div class="form-group">
                        <label for="parent_id">Parent</label>
                        <select class="form-control" name="parent_id" id="parent_id">
                            @foreach($mainCategories as $mainCategory)
                                <option value="{{$mainCategory->id}}" {{old('parent_id')===$mainCategory->id ? 'selected' : ''}}>{{$mainCategory->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Create</button>
                </form>
            </div>
        </div>
    </div>
@stop