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


