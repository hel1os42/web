@if (session()->has('message'))
<div class="alert alert-info">
    {{session('message')}}
</div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
@endif

@if (session()->has('warning'))
    <div class="alert alert-warning">
        {{session('warning')}}
    </div>
@endif

@if (isset($errors) && $errors->any())
    @php $messages = array_diff_key($errors->messages(), $fields); @endphp
    @if (!empty($messages))
        <div class="alert alert-danger">
            <ul>
            @foreach ($messages as $error)
                <li>{{ implode(', ', $error) }}</li>
            @endforeach
            </ul>
        </div>
    @endif
@endif
