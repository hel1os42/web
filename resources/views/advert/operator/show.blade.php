@extends('layouts.master')

@section('title', 'Show operator detail\'s')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            <h1>Operator</h1>
            <table>
                @foreach(get_defined_vars()['__data'] as $field => $value)
                    @if (!in_array($field, ['app', 'errors', '__env', 'authUser', 'variablesForFront', 'place']))
                        <tr>
                            <th>{{ $field }}:</th>
                            <td>
                                @if('is_active' === $field)
                                    {{ true === $value ? 'Active' : 'Deactive' }}
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
            <div>
                <form method="POST" action="{{ route('advert.operators.destroy', $id) }}" class="form-operator-delete">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-default">Delete operator</button>
                </form>
                <a href="{{ route('advert.operators.edit', $id) }}" class="btn btn-default">Edit operator</a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    table { margin: 24px 0; }
    table th, table td { padding: 4px 8px; }
    .form-operator-delete { display: inline-block; }
</style>
@endpush

@push('scripts')
    <script>
        document.querySelector('.form-operator-delete [type="submit"]').addEventListener('click', function(){
            setTimeout(function(){ location.replace('{{ route("advert.operators.index") }}'); }, 100);
        });
    </script>
@endpush

@stop
