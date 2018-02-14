@extends('layouts.master')
@section('title', 'Tag info')
@section('content')
    <div class="container">
        <div class="row">
            @include('partials.navbar', [
                'title' => 'Show tag',
                'leftLinksBar' => [
                    ['link' => route('tags.index'), 'text' => '<i class="fa fa-arrow-left" aria-hidden="true"></i> back to list'],
                    ['link' => route('tags.edit', $id), 'text' => '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> edit'],
                ],
                'rightLinksBar' => [
                    ['link' => route('tags.create'), 'text' => '<i class="fa fa-plus-circle" aria-hidden="true"></i> add new tag']
                ]
            ])
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
                    <tr>
                        <td>
                            Slug:
                        </td>
                        <td>
                            {{$slug}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Category:
                        </td>
                        <td>
                            {{$category['name']}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop