@php
    $value = isset($value) ? $value : null;
    $params = isset($params) ? (is_array($params) ? $params : [$params]) : [];
@endphp
<div class="form-group @if(isset($errors) && $errors->has($name)) has-error @endif">
    @isset($label)
        <label>
            {{$label}}
        </label>
    @endif
    @if($type == "password")
        {!! Form::password($name, $params) !!}
    @elseif($type == "select")
        {!! Form::select($name, $values, $value, $params) !!}
    @elseif(in_array($type, ["checkbox", "radio"]))
        {!! Form::input($type, $name, $value, $values, $params) !!}
    @elseif($type == "submit")
        {!! Form::submit($label) !!}
    @else
        {!! Form::input($type, $name, $value, $params) !!}
    @endif
    @if(isset($errors))
        @foreach($errors->get($name) as $message)
            <p class="text-danger">
                {{$message}}
            </p>
        @endforeach
    @endif
</div>
    
