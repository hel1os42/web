@extends('layouts.master')

@section('title', 'Create tag')

@section('content')
    <div class="container">
        <div class="row">
            @include('partials.navbar', [
                'title' => 'Create tag',
                'leftLinksBar' => [
                    ['link' => route('tags.index'), 'text' => '<i class="fa fa-arrow-left" aria-hidden="true"></i> back to list']
                ]
            ])
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <form action="{{route('tags.store')}}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}">
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{old('slug')}}">
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            @foreach($mainCategories as $mainCategory)
                                <option value="{{$mainCategory->id}}" {{old('category_id')===$mainCategory->id ? 'selected' : ''}}>{{$mainCategory->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Create</button>
                </form>
            </div>
        </div>
    </div>
@stop