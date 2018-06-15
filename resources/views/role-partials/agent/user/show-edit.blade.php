@can('user.update.children', [$editableUserModel, $children->pluck('id')->toArray()])
    <div class="row">
        <div class="col-sm-3">
            {{ __('users.fields.children') }}
        </div>

        <div class="col-sm-9 p-5">
            @if(isset($children))
                @php $box_style = $children->count() ? 'box-style' : ''; @endphp
                <div class="children-wrap {{ $box_style }}">
                    <input type="hidden" name="child_ids[]">

                    @foreach($children as $child)
                        <p>
                            <input type="hidden"
                                   class="added-children"
                                   name="child_ids[]"
                                   value="{{ $child['id'] }}"
                                   data-roles="{{ $child['roles']->pluck('name')->implode(',') }}"
                            >
                            <strong>{{ $child['name'] }} ({{ $child['email'] ?: $child['phone'] }})</strong>
                            <button type="button" class="close rm_child">×</button>
                        </p>
                    @endforeach
                </div>
            @endif
            <button id="add_children" class="btn" type="button" data-toggle="modal" data-target="#add_children_list">
                {{ __('buttons.add_children') }}
            </button>

        </div>
    </div>
@endcan