@extends('layouts.master')

@section('title', 'NAU show Users list')

@section('content')
    <style>
        td{
            padding:10px;
        }
    </style>
    <h1>Admin users list page.</h1>
    <h4>Search:</h4>
    <div id="admin-users-search">
        <label for="phone">By phone:</label>
        <input type="text" name="phone" id="phone" value="">
        <label for="role">By role:</label>
        <select name="role" id="role">
            <option value="" selected>All</option>
            <option value="admin">Admin</option>
            <option value="agent">Agent</option>
            <option value="chief_advertiser">Chief advertiser</option>
            <option value="advertiser">Advertiser</option>
            <option value="user">User</option>
        </select>
        <form method="get" action="{{route('users.index')}}" id="search-form">
            <input type="hidden" name="search" id="search-field" value="">
            <input type="hidden" name="searchJoin" value="and">
            <button type="submit">Search</button>
        </form>
    </div>


    <h2>Users list:</h2>
    <table style="font-family: serif; color:black; font-size: 26px; text-align: left;">
        <thead>
        <td>Name</td>
        <td>Email</td>
        <td>Phone</td>
        <td>Roles</td>
        <td>Actions</td>
        </thead>
        @foreach ($data as $user)

            <tr>
                <td>{{$user['name']}}</td>
                <td>{{$user['email']}}</td>
                <td>{{$user['phone']}}</td>
                <td>{{implode(', ', array_column($user['roles'], 'name'))}}</td>
                <td><a href="{{route('users.show', $user['id'])}}">edit</a> | <a href="">login as</a></td>
            </tr>
        @endforeach
    </table>
    @include('pagination.default', compact('current_page','from','last_page','next_page_url','path','per_page','prev_page_url','to','total'))

    <script type="text/javascript">
        let searchBlock = document.getElementById( 'admin-users-search' );
        let phoneInput = searchBlock.querySelector( '#phone' );
        let roleSelect = searchBlock.querySelector( '#role' );

        var updateAdminUsersSearchForm = function() {
            let result = '';
            if ( phoneInput.value !== '' ) {
                result = 'phone:' + phoneInput.value;
            }
            if ( phoneInput.value !== '' && roleSelect.value !== '' ) {
                result += ';';
            }
            if ( roleSelect.value !== '' ) {
                result += 'roles.name:' + roleSelect.value;
            }
            searchBlock.querySelector( '#search-field' ).value = result;
        };

        phoneInput.addEventListener( "input", updateAdminUsersSearchForm );
        roleSelect.addEventListener( "change", updateAdminUsersSearchForm );
    </script>
@stop