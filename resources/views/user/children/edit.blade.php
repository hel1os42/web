@php
    $rolesNames = array_map(function($role) {
        return $role['name'];
    }, $roles);
@endphp

<div role="tabpanel" class="tab-pane" id="children" data-children-uri="{{ route('users.children', $userId) }}">
    <form id="children_deleting" action="{{ route('users.children', $userId) }}" method="POST" enctype="application/x-www-form-urlencoded"
          data-user-roles="{{ implode(',', $rolesNames) }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}

        <div class="messages"></div>

        <div class="text-right">
            <button id="add_children" class="btn" type="button" data-toggle="modal" data-target="#add_children_list">
                {{ __('buttons.add_children') }}
            </button>
        </div>

        <div class="table_wrap">
            <table class="table-striped-nau table-users" id="children_list">
                <thead>
                <tr>
                    <th>{{ __('users.fields.name') }}</th>
                    <th>{{ __('users.fields.email') }}</th>
                    <th>{{ __('users.fields.phone') }}</th>
                    <th>{{ __('users.fields.roles') }}</th>
                    <th></th> {{-- buttons --}}
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <img src="{{ asset('img/loading.gif') }}" alt="wait...">
            <div class="blocker"></div>
        </div>

        <p class="total">{{ __('words.total') }} <span></span></p>
        <p class="pagenavy" id="children_pagination"></p>
    </form>
</div>

@push('styles')
    <style>
        .total {
            margin: 20px 10px 0px;
            font-style: italic;
            font-size: 14px;
        }
        #children .messages {
            padding: 0 5px;
        }
        .table_wrap.disabled {
            position: relative;
            color: gray;
        }
        img[src*="loading"] {
            display: none;
            margin: 0;
        }
        #children .table_wrap.disabled > img {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-left: -32px;
            display: block;
        }
        .table_wrap.disabled .blocker {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/children-edit.js') }}"></script>
@endpush
