@extends('layouts.master')
@section('title', 'Category list')
@section('content')
    <div class="container">
        <nav class="row">
            @include('partials.navbar', [
                'title' => 'Categories & retail types',
                'rightLinksBar' => [
                    ['link' => route('categories.create'), 'text' => '<i class="fa fa-plus-circle" aria-hidden="true"></i> add new retail type']
                ]
            ])
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Retail types</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($mainCategories as $mainCategory)
                        <tr class="table-primary">
                            <th scope="row">
                                <div class="placeholder category-icon-small">
                                    <div class="category-icon-small" style="background-image:url('{{$mainCategory->picture_url}}');"></div>
                                </div>
                                <a href="{{route('categories.show', [$mainCategory->id, 'with' => 'children'])}}">{{$mainCategory->name}}</a>
                                &nbsp;({{$mainCategory->children_count}})
                            </th>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td>
                                <table class="table table-hover" style="width: 100%">
                                    <tbody>
                                    @foreach($data as $category)
                                        @if($category['parent_id'] === $mainCategory->id)
                                            <tr>
                                                <td>
                                                    <div class="placeholder category-icon-small">
                                                        <div class="category-icon-small" style="background-image:url('{{$category['picture_url']}}');"></div>
                                                    </div>
                                                    <a href="{{route('categories.show', $category['id'])}}">{{$category['name']}}</a>
                                                </td>
                                                <td>
                                                    <a href="{{route('categories.edit', $category['id'])}}"><i
                                                                class="fa fa-pencil-square-o"
                                                                aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </nav>
    </div>
@stop
@push('styles')
    <style>
        .category-icon-small {
            background-repeat: no-repeat;
            background-size: contain;
            width: 24px;
            height: 19px;
            display:  inline-block;
            padding-top: 3px;
        }
        .category-icon-small.placeholder {
            background-image:url('/img/imagenotfound.svg');
        }
    </style>
@endpush