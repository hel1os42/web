<?php

namespace App\Providers;

use App\Models\NauModels\Offer;
use App\Models\User;
use App\Observers\OfferObserver;
use App\Observers\UserObserver;
use App\Repositories\AccountRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\PlaceRepository;
use App\Services\Implementation\InvestorAreaService as InvestorAreaServiceImpl;
use App\Services\Implementation\NauOfferReservation;
use App\Services\Implementation\WeekDaysService as WeekDaysServiceImpl;
use App\Services\Implementation\PlaceService as PlaceServiceImpl;
use App\Services\InvestorAreaService;
use App\Services\NauOffersService;
use App\Services\OfferReservation;
use App\Services\OffersService;
use App\Services\PlaceService;
use App\Services\WeekDaysService;
use App\Services\ImageService as ImageServiceInterface;
use App\Services\Implementation\ImageService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

/**
 * Class AppServiceProvider
 * @package App\Providers
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function boot()
    {
        Relation::morphMap([
            'users' => User::class
        ]);

        Offer::observe(OfferObserver::class);
        User::observe(UserObserver::class);

        ViewFacade::composer(
            ['*'], function (View $view) {
                $authUser = auth()->user();
                if (null != $authUser && null == array_get($view->getData(), 'authUser')) {
                    $authUser->load('accounts');
                    $view->with('authUser', $authUser->toArray());

                    $placesRepository = app(PlaceRepository::class);
                    $view->with('isPlaceCreated', $placesRepository->existsByUser($authUser));
                }
            }
        );

        ViewFacade::composer(
            ['category.*', 'tag.*'],
            function (View $view) {
                $categoryRepository = app(CategoryRepository::class);
                $view->with('mainCategories', $categoryRepository->getWithNoParent()->get());
            }
        );

        $this->setUserViewsData();


        Validator::extend('uniqueCategoryIdAndSlug', function ($attribute, $value, $parameters, $validator) {
            $count = \DB::table('tags')->where('id', '<>', $parameters[1])
                       ->where('category_id', $value)
                       ->where('slug', $parameters[0])
                       ->count();
            return $count === 0;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            OffersService::class,
            NauOffersService::class);
        $this->app->bind(
            WeekDaysService::class,
            WeekDaysServiceImpl::class
        );
        $this->app->bind(
            OfferReservation::class,
            NauOfferReservation::class);
        $this->app->bind(
            InvestorAreaService::class,
            InvestorAreaServiceImpl::class
        );
        $this->app->bind(
            ImageServiceInterface::class,
            ImageService::class
        );
        $this->app->bind(
            PlaceService::class,
            PlaceServiceImpl::class
        );
    }

    private function setUserViewsData()
    {
        ViewFacade::composer(
            ['user.show'], function (View $view) {
                $editableUserArray = $view->getData();
                /** @var User $editableUserModel */
                $editableUserModel = User::query()->find($editableUserArray['id']);
                $roleIds           = array_column(\App\Models\Role::query()->get(['id'])->toArray(), 'id');
                $children          = $editableUserModel->children->toArray();

                if (auth()->user()->isAdmin()) {
                    $allChildren = \App\Models\User::query()->get();
                } else {
                    $allChildren = auth()->user()->children;
                }

                $allPossibleChildren = [];


                if ($editableUserModel->isAgent()) {
                    $rolesForChildSet = [\App\Models\Role::ROLE_CHIEF_ADVERTISER, \App\Models\Role::ROLE_ADVERTISER];
                } else {
                    $rolesForChildSet = [\App\Models\Role::ROLE_ADVERTISER];
                }

                foreach ($allChildren as $childValue) {
                    if ($childValue->hasRoles($rolesForChildSet)) {
                        $allPossibleChildren[] = $childValue->toArray();
                    }
                }

                $view->with('roleIds', $roleIds);
                $view->with('children', $children);
                $view->with('allPossibleChildren', $allPossibleChildren);
                $view->with('editableUserModel', $editableUserModel);
            }
        );

        ViewFacade::composer(
            ['user.index'], function (View $view) {
                $specialUsersArray = \App\Models\NauModels\User::getSpecialUsersArray();
                $accountRepository = app(AccountRepository::class);
                $accounts          = $accountRepository->findAndSortByOwnerIds(array_keys($specialUsersArray))->toArray();
                foreach ($accounts as $accountKey => $account) {
                    $accounts[$accountKey]['nau_owner_name'] = $specialUsersArray[$account['owner_id']];
                }

                $view->with('specialUserAccounts', $accounts);
            }
        );
    }
}
