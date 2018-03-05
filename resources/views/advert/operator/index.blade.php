@extends('layouts.master')

@section('title', 'List operators')

@section('content')

<div class="container">
    <h1>Operators</h1>
    <div class="table-responsive">
        @if(0 !== count($data))
        <table class="table table-hover">
            <thead class="text-primary">
                <tr>
                    @foreach (array_keys($data[0]) as $field)
                        <th>{{ $field }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $operator)
                    <tr class="clickable-table-row" data-uuid="">
                        @foreach($operator as $key => $field)
                            <td>@if($key == 'place')
                                    <?php $field = $key == 'place' ? $field['alias'] : $field?>
                                @endif
                                @if('is_active' === $key)
                                    {{ true === $field ? 'Active' : 'Deactive' }}
                                @else
                                    {{ empty($field) ? '-' : $field }}
                                @endif
                            </td>
                        @endforeach
                        <td>
                            <form method="POST" action="{{ route('advert.operators.destroy', $operator['id']) }}" class="form-operator-delete">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-default btn-sm">Delete</button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('advert.operators.edit', $operator['id']) }}" class="btn btn-default btn-sm">Edit</a>
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
        document.querySelectorAll('.form-operator-delete [type="submit"]').forEach(function(btn){
            btn.addEventListener('click', function(){
                setTimeout(function(){ location.reload(); }, 100);
            });
        });
    </script>
@endpush

@stop
