@extends('layouts.master')

@section('title', 'Show category')

@section('content')
    <div class="profile">
<pre>
id: {{$id}}
name: {{$name}}
parent: {{ var_dump($parent) }}
children_count: {{$children_count}}
children: {{ isset($children) ? var_dump($children) : null }}
</pre>
    </div>
@stop