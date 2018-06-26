<div id="add_children_list" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1">&times;</button>
                <h4 class="modal-title">{{ __('users.titles.children_list') }}</h4>
            </div>
            <div class="modal-body" style="padding-top: 0; padding-bottom: 0;">
                <p class="messages"></p>

                <form method="get" action="{{route('users.index')}}" id="search_children" style="display: inline-block;">
                    <input type="hidden" name="search" id="search-field" value="">
                    <input type="hidden" name="searchJoin" value="and">
                    <label for="search_field">{{ __('msg.profile.search_by_fields')  }}</label>
                    <p>
                        <input id="search_field" type="text" value="">
                        @if( auth()->user()->isAdmin() && $editableUserModel->isAgent() )
                            <label>
                                <select name="role" id="role">
                                    <option class="all-roles" value="chief_advertiser|advertiser" selected>{{ __('words.all_roles') }}</option>
                                    <option value="chief_advertiser">{{ __('words.chief_advertiser') }}</option>
                                    <option value="advertiser">{{ __('words.advertiser') }}</option>
                                    <option value="user">{{ __('words.user') }}</option>
                                </select>
                            </label>
                        @endif
                        <button type="submit" class="btn" style="margin-left: 20px;">Search</button>
                    </p>
                </form>

                <form id="update_children_form" action="{{ route('users.children', $id) }}" method="POST" enctype="application/x-www-form-urlencoded">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div style="overflow-x: scroll">
                        <table class="table-striped-nau" style="margin-top: 10px;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>{{ __('users.fields.name') }}</th>
                                    <th>{{ __('users.fields.contacts') }}</th>
                                    <th>{{ __('users.fields.place') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{--only for reading bottom border color--}}
                                <tr><td></td></tr>
                            </tbody>
                        </table>
                    </div>
                </form>

                <img src="{{ asset('img/loading.gif') }}" alt="wait...">

                <p class="pagenavy" id="modal_pagination"></p>
            </div>
            <div class="modal-footer" style="padding-bottom: 15px;">
                {{--<button type="button" class="btn btn-default" id="add_selected_children">{{ __('buttons.add_selected_users') }}</button>--}}
                <button type="button" data-dismiss="modal" class="btn btn-default" id="attach_selected_children">{{ __('buttons.attach_selected_users') }}</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .modal-body {
        min-height: 230px;
    }
    .rm_child {
        clear: both;
        float: none;
        vertical-align: top;
    }

    .children-wrap .table-striped-nau tbody {
        opacity: 0;
        transition: opacity 0.3s, visibility 0.3s;
    }
    .children-wrap .table-striped-nau td {
        padding: 5px;
    }
    #add_selected_children {
        margin-right: 5px;
    }
    #role {
        height: 28px;
    }
    .pagenavy, .pagenavy > * {
        margin: 0;
    }
    img[src*="loading"] {
        position: relative;
        top: 10px;
        left: 50%;
        margin-left: -32px;
        margin-bottom: 20px;
        transition: opacity 1s, height 1s;
    }
    .error {
        color: red;
    }
</style>
@endpush

@push('scripts')
    <script>
        window.nau_lang = {!! json_encode(__('js_messages')) !!};
    </script>
    <script src="{{ asset('js/children-modal.js') }}"></script>
@endpush
