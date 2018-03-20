@extends('layouts.master')

@section('title', 'List operators')

@section('content')

<div class="container">
    <h1>Operators</h1>
    <div>
        @if(0 !== count($data))
        <table class="table-striped-nau" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Place uuid</th>
                    <th>Alias</th>
                    <th>Login</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $operator)
                    <tr data-uuid="">
                        <td>{{ $operator['id'] }}</td>
                        <td>{{ $operator['place_uuid'] }}</td>
                        <td>
                            {{ empty($operator['place']['alias']) ? '-' : $operator['place']['alias'] }}
                        </td>
                        <td>{{ $operator['login'] }}</td>
                        <td>
                            {{ true === $operator['is_active'] ? 'Active' : 'Deactive' }}
                        </td>
                        <td>{{ $operator['created_at'] }}</td>
                        <td>{{ $operator['updated_at'] }}</td>
                        <td>
                            <form method="POST" action="{{ route('advert.operators.update', $operator['id']) }}" class="form-operator-status">
                                <input type="hidden" name="_method" value="PATCH">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="is_active" value="{{ $operator['is_active'] ? 0 : 1}}">
                                <input type="hidden" name="place_uuid" value="{{ $operator['place_uuid'] }}">
                                <input type="hidden" name="id" value="{{ $operator['id'] }}">
                                <button type="submit" class="btn btn-default btn-sm">Status</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('advert.operators.destroy', $operator['id']) }}" class="form-operator-delete">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-default btn-sm">Delete</button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('advert.operators.edit', $operator['id']) }}" class="btn btn-default btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p style="padding: 40px 0;">No operators</p>
        @endif
        <p><a href="{{ route('advert.operators.create') }}" class="btn btn-nau">Create operator</a></p>
    </div>
</div>

@push('scripts')
    <script>
        document.querySelectorAll('.form-operator-delete [type="submit"]').forEach(function(btn){
            btn.addEventListener('click', function(){
                setTimeout(function(){ location.reload(); }, 100);
            });
        });
    </script>
@endpush

@stop
