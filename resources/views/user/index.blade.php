@extends('layouts.master')

@section('title', 'NAU show advertiser list')

@section('content')

    <div class="container" style="margin-top: 40px;">
        @include('role-partials.selector', ['partialRoute' => 'user.index-head'])
        <table class="table-striped-nau">
            <thead>
                <tr>
                    <th>User</th>
                    <th></th>
                    <th>Place</th>
                    <th></th>
                    <th>Balance</th>
                    <th></th>
                    <th>Approved</th>
                    <th>Actions</th>
                </tr>
            </thead>
            @foreach ($data as $user)
                <tr>
                    <td>{{$user['name'] ?: '-'}}
                        <br>
                        <span>{{$user['email']}}</span><br>
                        <span>{{$user['phone']}}</span>
                    </td>
                    <td>
                        <a href="{{route('users.show', $user['id'])}}">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    </td>

                @if(isset($user['place']['id']))
                    <td>
                        <a href="{{route('places.show', $user['place']['id'])}}"> {{$user['place']['name']}} </a>
                    </td>
                    <td>
                        <a href="{{route('places.edit', $user['place']['id'])}}">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    </td>
                @else
                    <td>-</td>
                    <td></td>
                @endif

                @if(isset($user['accounts']['NAU']['balance']))
                    <td>
                        {{$user['accounts']['NAU']['balance']}}
                    </td>
                    <td>
                        <button type="submit"
                                class="btn btn-sm transaction-open-dialog"
                                data-toggle="modal"
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
                <td>
                    @include('role-partials.selector', ['partialRoute' => 'user.index-approved', 'data' => ['user' => $user]])
                </td>
                <td>
                    <a href="{{route('impersonate', $user['id'])}}">login as</a>
                </td>
            </tr>
            @endforeach
        </table>
    @include('pagination.default', compact('current_page','from','last_page','next_page_url','path','per_page','prev_page_url','to','total'))
</div>

<div class="modal fade" id="sendNauModal" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send NAU</h4>
            </div>
            <div class="transaction modal-body" data-url="{{route('transaction.complete')}}">
                {{ csrf_field() }}
                @include('role-partials.selector', ['partialRoute' => 'user.index-balance-form', 'data' => ['specialUserAccounts' => $specialUserAccounts]])
                <input type="hidden" name="destination" id="destination" value="">
                <input type="hidden" name="no_fee" id="noFee" value="1">
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
@stop

@push('scripts')
<script type="text/javascript">

    searchForm();

    fillSearchForm();

    pagenavyCompact(document.getElementById('table_pager'));

    userStatusControl();

    function searchForm(){
        let searchBlock = document.getElementById('admin-users-search');
        let searchForm = searchBlock.querySelector('#search-form');
        let emailInput = searchBlock.querySelector('#email');
        let roleSelect = searchBlock.querySelector('#role');

        let searchOptions = location.search.substr(1).split('&');
        searchOptions = searchOptions.map(function(e){ return e.split('='); });
        let orderByText = searchOptions.find(function(e){ return e[0] === 'orderBy' });
        let sortedByText = searchOptions.find(function(e){ return e[0] === 'sortedBy' });
        if (orderByText) {
            let orderBy = document.createElement('input');
            orderBy.type = 'hidden';
            orderBy.name = 'orderBy';
            orderBy.value = orderByText[1];
            searchForm.appendChild(orderBy);
        }
        if (sortedByText) {
            let sortedBy = document.createElement('input');
            sortedBy.type = 'hidden';
            sortedBy.name = 'sortedBy';
            sortedBy.value = sortedByText[1];
            searchForm.appendChild(sortedBy);
        }

        function updateAdminUsersSearchForm() {
            let result = '';
            if ( emailInput.value !== '' ) result = 'email:' + emailInput.value;
            if ( emailInput.value !== '' && roleSelect.value !== '' ) result += ';';
            if ( roleSelect.value !== '' ) result += 'roles.name:' + roleSelect.value;
            searchBlock.querySelector('#search-field').value = result;
        }

        emailInput.addEventListener( "input", updateAdminUsersSearchForm );
        if ( roleSelect ) {
            roleSelect.addEventListener( "change", updateAdminUsersSearchForm );
        }

    }

    function userStatusControl(){
        $('.user-approve-controls form').on('submit', function(e){
            e.preventDefault();

            let $box = $(this).parents('.user-approve-controls');
            let $user_status = $box.find('[name="approved"]');
            let $err = $box.find('.waiting-response');

            $box.removeClass('status-approved status-disapproved').addClass('status-wait');
            let formData = $(this).serializeArray();
            console.log('Change User Status:');
            console.dir(formData);

            $.ajax({
                method: "PATCH",
                url: $(this).attr('action'),
                headers: { 'Accept':'application/json' },
                data: formData,
                success: function(data, textStatus, xhr){
                    if (201 === xhr.status){
                        $box.removeClass('status-wait').addClass('status-' + ($user_status.val() === '0' ? 'dis' : '') + 'approved');
                        $user_status.val($user_status.val() === '0' ? '1' : '0');
                    } else {
                        $err.text('err-st: ' + xhr.status);
                        console.dir(xhr);
                    }
                },
                error: function(resp){
                    if (401 === resp.status) UnAuthorized();
                    else if (0 === resp.status) AdBlockNotification();
                    else {
                        $err.text('err-st: ' + resp.status);
                        console.dir(resp);
                        alert(`Error ${resp.status}: ${resp.responseText}`);
                    }
                }
            });
        });
    }

    function fillSearchForm(){
        console.log('Fill Search Form');
        let searchOptions = decodeURIComponent(location.search.substr(1)).split('&');
        searchOptions = searchOptions.map(function(e){ return e.split('='); });
        let search = searchOptions.find(function(e){ return e[0] === 'search' });
        if (search) {
            search = search[1].split(';');
            search = search.map(function(e){ return e.split(':'); });
            let searchByEmail = search.find(function(e){ return e[0] === 'email' });
            if (searchByEmail) document.getElementById('email').value = searchByEmail[1];
            let searchByRole = search.find(function(e){ return e[0] === 'roles.name' });
            let roleSelect = document.getElementById('role');
            if (searchByRole && roleSelect) {
                let options = roleSelect.children;
                for (let i = 0; i < options.length; i++) if (options[i].value === searchByRole[1]) options[i].selected = true;
            }
        }
    }

</script>
@endpush
