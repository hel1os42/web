@extends('layouts.master')

@section('title', 'Show category')

@section('content')
    <div class="profile">
        <ul>
            <li>id: {{$id}}</li>
            <li>code: {{$code}}</li>
            <li>user_id: {{$user_id}}</li>
            <li>offer_id: {{$offer_id}}</li>
            <li>redemption_id: {{$redemption_id}}</li>
            <li>Relations:
                <ul>
                    @if(isset($user))
                        <li>user:
                        <pre>{{print_r($user)}}</pre></li>
                    @endif
                    @if(isset($offer))
                            <li>offer:
                            <pre>{{print_r($offer)}}</pre></li>
                    @endif
                    @if(isset($redemption))
                            <li>redemption:
                            <pre>{{print_r($redemption)}}</pre></li>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
@stop