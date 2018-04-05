@can('user.update.children', [$editableUserModel, array_column($children, 'id')])

<div class="row">
    <div class="col-sm-3">
        <p><strong>Children</strong></p>
    </div>

    <div class="col-sm-9">
        @if(isset($children))
            <div class="children-wrap" style="padding-bottom: 16px;">
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
