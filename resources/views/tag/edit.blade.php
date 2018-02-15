@extends('layouts.master')

@section('title', 'Edit tag')

@section('content')
    <div class="container">
        <div class="row">
            @include('partials.navbar', [
                'title' => 'Edit tag',
                'leftLinksBar' => [
                    ['link' => route('tags.index'), 'text' => '<i class="fa fa-arrow-left" aria-hidden="true"></i> back to list</a>']
                ]
            ])
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <form action="{{route('tags.update', [$id])}}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{$name}}">
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{$slug}}">
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            @foreach($mainCategories as $mainCategory)
                                <option value="{{$mainCategory->id}}" {{$category['id']===$mainCategory->id ? 'selected' : ''}}>{{$mainCategory->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
@stop