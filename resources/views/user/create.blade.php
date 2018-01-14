@extends('layouts.master')

@section('title', 'Create user')
@php
    $roles = (new \App\Models\Role);
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9" style="margin-top: 50px">
                <form action="{{route('register')}}" method="POST"
                      enctype="application/x-www-form-urlencoded">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6 p-5">
                            <p><strong>Email</strong></p>
                            <p><strong>Password</strong></p>
                            <p><strong>Password confirm</strong></p>
                            <p><strong>Phone</strong></p>
                            <p><strong>Is it chief advertiser?</strong></p>
                        </div>
                        <div class="col-sm-6 p-10 p-5">
                            <p><input style="line-height: 14px; font-size: 14px;" type="text" name="email"
                                      value=""></p>
                            <p><input style="line-height: 14px; font-size: 14px;" type="password" name="password"
                                      placeholder="password"></p>
                            <p><input style="line-height: 14px; font-size: 14px;" type="password"
                                      name="password_confirm"
                                      placeholder="password_confirmation"></p>
                            <p><input style="line-height: 14px; font-size: 14px;" type="text" name="phone"
                                      value=""></p>
                            <p>
                                <input style="line-height: 14px; font-size: 14px;" type="radio"
                                       onclick="checkUserRole(true)"
                                       name="role_ids[]"
                                       value="{{$roles::findByName('advertiser')->getId()}}" checked> Advertiser + user
                            </p>
                            <input hidden style="line-height: 14px; font-size: 14px;" type="checkbox"
                                   id="role_ids_user"
                                   name="role_ids[]"
                                   value="{{$roles::findByName('user')->getId()}}" checked>

                            <p><input style="line-height: 14px; font-size: 14px;" type="radio"
                                      onclick="checkUserRole(false)"
                                      name="role_ids[]"
                                      value="{{$roles::findByName('chief_advertiser')->getId()}}"> Chief advertiser</p>


                            <input hidden style="line-height: 14px; font-size: 14px;" type="radio"
                                   onclick="checkUserRole(false)"
                                   name="parent_ids[]"
                                   value="{{auth()->user()->getId()}}" checked>
                        </div>
                        <button type="submit">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="application/javascript">
        function checkUserRole( userFlag ) {
            document.getElementById( 'role_ids_user' ).checked = userFlag;
        }
    </script>
@stop
