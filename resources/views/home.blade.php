@extends('layouts.master')

@section('title', 'NAU')

@section('content')
    <h1>Welcome to NAU {{$authUser['name']}}!</h1>
    <div class="col-md-9" style="margin-left:200px">
        @auth
            @if(auth()->user()->isAgent())
                <h3>Advertisers(All): {{count(auth()->user()->children)}}</h3>
                <h3>Advertisers(Approved): {{count(auth()->user()->children()->where('is_approved', true))}}</h3>
                <h3>Offers: {{auth()->user()->children->pluck('offers_count')->sum()}}</h3>
                <h3>Redemptions: {{auth()->user()->children->map(function (\App\Models\User $user) {
                return $user->offers->map(function (\App\Models\NauModels\Offer $offer) {return $offer->redemptions->count();})->sum();
            })->sum()}}</h3>
            @endif
        @endauth
    </div>
@stop