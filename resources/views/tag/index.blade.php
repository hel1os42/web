@extends('layouts.master')
@section('title', 'Tags list')
@section('content')
    <div class="container">
        <nav class="row">
            @include('partials.navbar', [
                'title' => 'Tags',
                'rightLinksBar' => [
                    ['link' => route('tags.create'), 'text' => '<i class="fa fa-plus-circle" aria-hidden="true"></i> add new tag']
                ]
            ])
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Main category</th>
                        <th scope="col">Tags</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($mainCategories as $mainCategory)
                        <tr class="table-primary">
                            <th scope="row">
                                {{$mainCategory->name}}
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
                                    @foreach($data as $tag)
                                        @if($tag['category_id'] === $mainCategory->id)
                                            <tr>
                                                <td>
                                                    <a href="{{route('tags.show', $tag['id'])}}">{{$tag['name']}}</a>
                                                </td>
                                                <td>
                                                    <a href="{{route('tags.edit', $tag['id'])}}"><i
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