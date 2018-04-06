<div class="row">
    <div class="col-lg-10">
        <p><strong>Invite </strong><label><input style="line-height: 14px; font-size: 14px;" type="text" name="invite_code"
                         value="{{ $invite_code }}"></label></p>
    </div>
    <div class="col-sm-6">
        @can('user.update.roles', [$editableUserModel, $roleIds])
            <p><strong>Roles <span style="color:red">(Warning! Changing role can damage user. Use it only when user just created, or in critical situations.)</span></strong></p>
        @endcan
    </div>
    <div class="col-sm-6">
        @can('user.update.roles', [$editableUserModel, $roleIds])
            <div id="roles" style="padding-bottom: 16px;"></div>
        @endcan
    </div>
    <script>
        (function(){
            
            loadRoles();

            function loadRoles() {
                let xhr = new XMLHttpRequest();
                xhr.responseType = 'json';
                let currentRoles = {!! json_encode(array_column($roles, 'id')) !!};

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 401) UnAuthorized();
                        else if (xhr.status === 0) AdBlockNotification();
                        else if (xhr.status === 200) {
                            console.dir(xhr.response);
                            let html = '', checked;
                            xhr.response.roles.forEach(function(e, i){
                                checked = currentRoles.indexOf(e.id) !== -1 ? ' checked' : '';
                                html += '<p><label><input type="checkbox" name="role_ids[' + i + ']" value="' + e.id + '"' + checked + '> ' + e.name + '</label></p>';
                            });
                            document.getElementById('roles').innerHTML = html;
                        } else if ( xhr.status === 400 ) {
                            alert( 'There was an error 400' );
                        } else {
                            alert( xhr.status + ' was returned' );
                        }
                    }
                };

                xhr.open( "GET", "{{ route('roles') }}", true );
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.send();
            }
        })();
    </script>
</div>

@can('user.update.children', [$editableUserModel, array_column($children, 'id')])

<div class="row">
    <div class="col-sm-6">
        <p><strong>Set children</strong></p>
    </div>
    <div class="col-sm-6">
        @if(isset($children))
            <div class="children-wrap" style="padding-bottom: 16px;">
                <input type="hidden" name="child_ids[]">

                @foreach($children as $child)
                    <p>
                        <input type="hidden"
                               class="added-children"
                               name="child_ids[]"
                               value="{{ $child['id'] }}"
                        >
                        <strong>{{ $child['name'] }} ({{ $child['email'] }})</strong>
                        <button type="button" class="close rm_child">×</button>
                    </p>
                @endforeach
            </div>
        @endif
        <button id="add_children" class="btn" type="button" data-toggle="modal" data-target="#add_children_list">
            Add children
        </button>

    </div>
</div>

@endcan