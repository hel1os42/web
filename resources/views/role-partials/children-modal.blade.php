<div id="add_children_list" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1">&times;</button>
                <h4 class="modal-title">Children list</h4>
            </div>
            <div class="modal-body">

                <form method="get" action="{{route('users.index')}}" id="search_children"
                      style="display: inline-block;">
                    <input type="hidden" name="search" id="search-field" value="">
                    <input type="hidden" name="searchJoin" value="and">
                    <label for="search_field">Search by name, email, phone or place:</label>
                    <p>
                        <input id="search_field" type="text" value="">
                        @if( auth()->user()->isAdmin())
                            <label>
                                <select name="role" id="role">
                                    <option value="" selected>By All Roles</option>
                                    <option value="agent">Agent</option>
                                    <option value="chief_advertiser">Chief advertiser</option>
                                    <option value="advertiser">Advertiser</option>
                                    <option value="user">User</option>
                                </select>
                            </label>
                        @endif
                        <button type="submit" class="btn" style="margin-left: 20px;">Search</button>
                    </p>
                </form>

                <table class="table-striped-nau" style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Place</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{--only for reading bottom border color--}}
                        <tr><td></td></tr>
                    </tbody>
                </table>

                <img src="{{ asset('img/loading.gif') }}" alt="wait...">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="add_selected_children">Add selected users</button>
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

    .table-striped-nau tbody {
        opacity: 0;
        transition: opacity 0.3s, visibility 0.3s;
    }
    .table-striped-nau td {
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
    <script src="{{ asset('js/children-modal.js') }}"></script>
@endpush