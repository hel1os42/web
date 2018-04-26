@extends('layouts.master')

@section('title', 'List operators')

@section('content')

@push('styles')
<style>
    span.uuid-title { display: inline-block; min-width: 80px;}
    span.uuid { white-space: nowrap;}
    form.form-operator-delete { display: inline-block;}
    .table-striped-nau th:first-child,
    .table-striped-nau td:first-child { width: 450px;}
    th { padding-left: 10px; padding-right: 10px;}
    .operator-buttons a { display: inline-block; margin: 0 10px; padding: 5px 10px; font-size: 150%;}
    .operator-buttons .remove-operator { display: inline-block; margin: 0 10px; padding: 5px 10px; font-size: 150%; background: none; border: none; color: #ff5a00;}
    .operator-buttons .remove-operator:hover { color: #ff8a0e;}
    .operator-status-box .os-active,
    .operator-status-box .os-deactive,
    .operator-status-box .btn-active,
    .operator-status-box .btn-deactive { display: none;}
    .operator-status-box .os-active { color: green;}
    .operator-status-box .os-deactive { color: red;}
    .operator-status-box.status-active .os-active,
    .operator-status-box.status-active .btn-active,
    .operator-status-box.status-deactive .os-deactive,
    .operator-status-box.status-deactive .btn-deactive {
        display: inline-block;
    }
</style>
@endpush

<div class="container">
    <h1>Operators</h1>
    <div>
        @if(0 !== count($data))
        <table class="table-striped-nau" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>UUID</th>
                    <th>Alias</th>
                    <th>Login</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Created/updated</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $operator)
                    <tr data-uuid="">
                        <td>
                            <span class="uuid-title">Operator:</span> <span class="uuid">{{ $operator['id'] }}</span><br>
                            <span class="uuid-title">Place:</span> <span class="uuid">{{ $operator['place_uuid'] }}</span>
                        </td>
                        <td>{{ empty($operator['place']['alias']) ? '-' : $operator['place']['alias'] }}</td>
                        <td>{{ $operator['login'] }}</td>
                        <td class="text-center operator-status-box status-{{ true === $operator['is_active'] ? 'active' : 'deactive' }}">
                            <span class="os-active">Active</span>
                            <span class="os-deactive">Deactive</span>
                            <form method="POST" action="{{ route('advert.operators.update', $operator['id']) }}" class="form-operator-status">
                                <input type="hidden" name="_method" value="PATCH">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="is_active" value="{{ $operator['is_active'] ? 0 : 1}}">
                                <input type="hidden" name="place_uuid" value="{{ $operator['place_uuid'] }}">
                                <input type="hidden" name="id" value="{{ $operator['id'] }}">
                                <button type="submit" class="btn btn-default btn-xs btn-active">Deactivate</button>
                                <button type="submit" class="btn btn-default btn-xs btn-deactive">Activate</button>
                            </form>
                        </td>
                        <td class="text-center">
                            <span class="js-date">{{ $operator['created_at'] }}</span><br>
                            <span class="js-date">{{ $operator['updated_at'] }}</span>
                        </td>
                        <td class="operator-buttons text-center">
                            <form method="POST" action="{{ route('advert.operators.destroy', $operator['id']) }}" class="form-operator-delete">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="remove-operator" title="Remove operator"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                            </form>
                            <a href="{{ route('advert.operators.edit', $operator['id']) }}" title="Edit operator"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p style="padding: 40px 0;">No operators</p>
        @endif
        <p><a href="{{ route('advert.operators.create') }}" class="btn btn-nau">Create operator</a></p>
    </div>
</div>

@push('scripts')
<script>
(function(){

    document.querySelectorAll('.js-date').forEach(function(span){
        span.innerText = span.innerText.substr(0, 10);
    });

    document.querySelectorAll('.form-operator-delete').forEach(function(form){
        let btn = form.querySelector('button.remove-operator');
        if (btn) btn.addEventListener('click', function(e){
            if (!confirm('Are you sure to remove this operator?')) return false;
            e.preventDefault();
            form.submit();
            setTimeout(function(){ alert('Operator was removed.'); location.reload(true); }, 200);
        });
    });

})();
</script>
@endpush

@stop
