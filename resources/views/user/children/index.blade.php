@extends('layouts.master')

@section('title', 'NAU show advertiser list')

@section('content')

    <div class="container">
        <table class="table-striped-nau table-users">
            <thead>
            <tr>
                <th>{{ __('users.fields.name') }}</th>
                <th>{{ __('users.fields.email') }}</th>
                <th>{{ __('users.fields.phone') }}</th>
                <th>{{ __('users.fields.roles') }}</th>
            </tr>
            </thead>
            @foreach ($data as $user)
                @php
                    $roles = array_map(function($role) {
                        return __('words.' . $role['name']);
                    }, $user['roles']);
                @endphp

                <tr>
                    <td>
                        {{ $user['name'] ?: '-' }}
                    </td>
                    <td>
                        {{ $user['email'] ?: '-' }}
                    </td>
                    <td>
                        {{ $user['phone'] ?: '-' }}
                    </td>
                    <td>
                        {{ implode(', ', $roles) }}
                    </td>
                </tr>
            @endforeach
        </table>
        @include('pagination.default', compact('current_page','from','last_page','next_page_url','path','per_page','prev_page_url','to','total'))
    </div>

@stop
