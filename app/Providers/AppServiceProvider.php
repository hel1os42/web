<?php

namespace App\Providers;

use App\Models\NauModels\Offer;
use App\Observers\OfferObserver;
use App\Repositories\PlaceRepository;
use App\Services\Implementation\NauOfferReservation;
use App\Repositories\Criteria\MappableRequestCriteria;
use App\Repositories\Criteria\MappableRequestCriteriaEloquent;
use App\Services\NauOffersService;
use App\Services\OfferReservation;
use App\Services\OffersService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'users' => \App\Models\User::class
        ]);

        Offer::observe(OfferObserver::class);

        ViewFacade::composer(
            ['advert.*'], function (View $view) {
                $authUser = auth()->user();
                $authUser->load('accounts');
                $view->with('authUser', $authUser->toArray());

                $placesRepository = app(PlaceRepository::class);
                $view->with('isPlaceCreated', $placesRepository->existsByUser($authUser));
            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OffersService::class, NauOffersService::class);
        $this->app->bind(
            \App\Services\WeekDaysService::class,
            \App\Services\Implementation\WeekDaysService::class
        );
        $this->app->bind(OfferReservation::class, NauOfferReservation::class);
        $this->app->bind(
            MappableRequestCriteria::class,
            MappableRequestCriteriaEloquent::class
        );
    }
}
