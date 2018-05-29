<div class="row">
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

<div class="row">
    <div class="col-sm-6">
        @can('user.update.children', [$editableUserModel, array_column($allPossibleChildren, 'id')])
            <p><strong>Set children</strong></p>
        @endcan
    </div>
    <div class="col-sm-6">
        @can('user.update.children', [$editableUserModel, array_column($allPossibleChildren, 'id')])
            @if(isset($allPossibleChildren))
                @php
                    $children = isset($children) ? $children : [];
                    $counter = 0;
                @endphp
                <div style="padding-bottom: 16px;">
                    @foreach($allPossibleChildren as $child)
                        <p>
                            <label>
                                <input type="checkbox"
                                       name="child_ids[{{ $counter }}]"
                                       value="{{ $child['id'] }}"
                                       @foreach($children as $selectedChild)
                                       @if($selectedChild['id'] === $child['id'])
                                       checked
                                       @endif
                                       @endforeach
                                > {{ $child['name'] }} <small>{{ $child['email'] }}</small>
                            </label>
                        </p>
                        @php
                            $counter++;
                        @endphp
                    @endforeach
                </div>
            @endif
        @endcan
    </div>
</div>
