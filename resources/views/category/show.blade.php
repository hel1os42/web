@extends('layouts.master')
@section('title', 'Category list')
@section('content')
    <div class="container">
        <div class="row">
            @include('partials.navbar', [
                'title' => 'Show category(retail type)',
                'leftLinksBar' => [
                    ['link' => route('categories.index'), 'text' => '<i class="fa fa-arrow-left" aria-hidden="true"></i> back to list'],
                    ['link' => route('categories.edit', $id), 'text' => '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> edit'],
                ],
                'rightLinksBar' => [
                    ['link' => route('categories.create'), 'text' => '<i class="fa fa-plus-circle" aria-hidden="true"></i> add new retail type']
                ]
            ])
            <div class="category-icon-block">
                <form action="{{route('categories.picture.store', $id)}}" method="POST" enctype="multipart/form-data">
                    <div class="placeholder category-icon">
                        <div class="category-icon" style="background-image:url('{{$picture_url}}')"></div>
                    </div>
                    {{ csrf_field() }}
                    <div class="input-group">
                        <div class="form-group">
                            <label for="category-picture">Choose icon file</label>
                            <input name="picture" type="file" class="form-control-file" id="category-picture"
                                   accept=".svg">
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Edit icon</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                        <th scope="row">
                            Name:
                        </th>
                        <td>
                            {{$name}}
                        </td>
                    </tr>
                    @if(isset($parent) || !empty($parent))
                        <tr>
                            <td>
                                Parent:
                            </td>
                            <td>
                                <a href="{{route('categories.show', $parent['id'])}}">{{$parent['name']}}</a>
                            </td>
                        </tr>
                    @endif
                    @if(isset($children))
                        <tr>
                            <td>
                                Children count:
                            </td>
                            <td>
                                {{$children_count}}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Retail types:
                            </td>
                            <td>
                                <table class="table table-hover" style="width: 100%">
                                    <tbody>
                                    @foreach($children as $category)
                                        <tr>
                                            <td>
                                                <a href="{{route('categories.show', $category['id'])}}">{{$category['name']}}</a>
                                            </td>
                                            <td>
                                                <a href="{{route('categories.edit', $category['id'])}}"><i
                                                            class="fa fa-pencil-square-o"
                                                            aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('styles')
        <style>
            .category-icon-block {
                text-align: center;
                margin-left: 38%;
                width: 400px;
                height: 300px;
            }

            .category-icon {
                width: 100%;
                margin-left: 15%;
                margin-right: auto;
                height: 168px;
                background-repeat: no-repeat;
                background-size: contain;
            }
            .category-icon.placeholder {
                background-image:url('/img/imagenotfound.svg');
            }
        </style>
    @endpush
@stop