@extends('layouts.master')

@section('title', 'NAU show advertiser list')

@section('content')
    <style>
        td {
            padding: 10px;
        }
    </style>
    <h1>Advertiser list</h1>
    <div class="col-md-9" style="margin-left:200px; margin-top: 40px;">
        <div class="card card-very-long">

            <a href="{{route('users.create')}}" style="float:right" class="btn-link">+ Add new advertiser</a>
            <div id="admin-users-search">
                <label for="phone">By email:</label>
                <input type="text" name="email" id="email" value="">
                @if(auth()->user()->isAdmin())
                    <label for="role">By role:</label>
                    <select name="role" id="role">
                        <option value="" selected>All</option>
                        <option value="admin">Admin</option>
                        <option value="agent">Agent</option>
                        <option value="chief_advertiser">Chief advertiser</option>
                        <option value="advertiser">Advertiser</option>
                        <option value="user">User</option>
                    </select>
                @endif

                <form method="get" action="{{route('users.index')}}" id="search-form" style="display: inline-block;">
                    <input type="hidden" name="search" id="search-field" value="">
                    <input type="hidden" name="searchJoin" value="and">
                    <button type="submit" class="btn">Search</button>
                </form>
            </div>
            <table style="font-family: serif; color:black; font-size: 26px; text-align: left; margin-top: 50px;">
                <thead>
                <td>Name</td>
                <td>Place</td>
                <td>Email</td>
                <td>Phone</td>
                <td>Balance(NAU)</td>
                <td></td>
                @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
                    <td>Approved</td>
                @endif
                <td>Actions</td>
                </thead>
                @foreach ($data as $user)

                    <tr>
                        <td>{{$user['name']}}</td>
                        <td>
                            @if(isset($user['place']['id']))
                                <a href="{{route('places.show', $user['place']['id'])}}"> {{$user['place']['name']}} </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{$user['email']}}</td>
                        <td>{{$user['phone']}}</td>
                        @if(isset($user['accounts']['NAU']['balance']))
                            <td>
                                {{$user['accounts']['NAU']['balance']}}
                            </td>
                            <td>
                                <button style="display:  inline-block;" type="submit"
                                        class="btn transaction-open-dialog"
                                        data-toggle="modal"
                                        data-source="{{auth()->user()->getAccountForNau()->getAddress()}}"
                                        data-destination="{{$user['accounts']['NAU']['address']}}"
                                        data-target="#sendNauModal">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </button>
                            </td>
                        @else
                            <td>
                                - <i class="fa fa-info" aria-hidden="true" style="color: red;"></i>
                            </td>
                            <td></td>
                        @endif
                        @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
                            <td>
                                @if(in_array('advertiser', array_column($user['roles'], 'name')))
                                    @if($user['approved'])
                                        <span style="color:green">Yes</span>
                                    @else
                                        <form action="{{route('users.update', $user['id'])}}" method="post"
                                              style="display:  inline-block;">
                                            No
                                            {{ csrf_field() }}
                                            {{ method_field('PATCH') }}
                                            <input hidden type="text" name="approved" value="1">
                                            <button style="display:  inline-block;" type="submit" class="btn">approve
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @endif
                        <td><a href="{{route('users.show', $user['id'])}}">edit</a> |
                            <a href="{{route('impersonate', $user['id'])}}">login as</a>
                        </td>
                    </tr>
                @endforeach
            </table>
            @include('pagination.default', compact('current_page','from','last_page','next_page_url','path','per_page','prev_page_url','to','total'))
        </div>
    </div>
    <div class="modal fade" id="sendNauModal" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send NAU</h4>
                </div>
                <div class="transaction modal-body" data-url="{{route('transaction.complete')}}">
                    <p>Your balance: {{auth()->user()->getAccountForNau()->getBalance()}}</p>
                    {{ csrf_field() }}
                    <input hidden type="text" name="source" id="source" value="">
                    <input hidden type="text" name="destination" id="destination" value="">
                    <input hidden type="text" name="no_fee" id="noFee" value="1">
                    <label for="amount">Amount</label>
                    <input type="text" name="amount" id="amount" value="1"> (min 1 NAU)
                </div>
                <div class="transaction-result modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="sendTransaction">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let searchBlock = document.getElementById( 'admin-users-search' );
        let phoneInput = searchBlock.querySelector( '#email' );
        let roleSelect = searchBlock.querySelector( '#role' );

        var updateAdminUsersSearchForm = function() {
            let result = '';
            if ( phoneInput.value !== '' ) {
                result = 'email:' + phoneInput.value;
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
        if ( roleSelect ) {
            roleSelect.addEventListener( "change", updateAdminUsersSearchForm );
        }

    </script>
@stop