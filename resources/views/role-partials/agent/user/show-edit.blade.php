<div class="col-sm-6 p-5">
    @can('user.update.children', [$editableUserModel, array_column($allPossibleChildren, 'id')])
        <p><strong>Set children</strong></p>
    @endcan
</div>
<div class="col-sm-6 p-5">
    @can('user.update.children', [$editableUserModel, array_column($allPossibleChildren, 'id')])
        <p>
            @if(isset($allPossibleChildren))
                @php
                    $children = isset($children) ? $children : [];
                @endphp
                <select style="height: 120px;" id="roles" name="child_ids[]"
                        class="form-control" multiple>
                    @foreach($allPossibleChildren as $child)
                        <option value="{{$child['id']}}"
                                @foreach($children as $selectedChild)
                                @if($selectedChild['id'] === $child['id'])
                                selected
                                @endif
                                @endforeach
                        >{{$child['name']}}({{$child['email']}})
                        </option>
                    @endforeach
                </select>
            @endif
        </p>
    @endcan
</div>