@if (session()->has('message'))
<div class="alert alert-info">
    {{session('message')}}
</div>
@endif
@if (isset($errors) && $errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

