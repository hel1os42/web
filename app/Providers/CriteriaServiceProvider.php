<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 21.11.17
 * Time: 4:42
 */

namespace App\Providers;

use App\Services\Criteria\MappableRequestCriteria;
use App\Services\Criteria\MappableRequestCriteriaEloquent;
use App\Services\Criteria\RequestCriteriaEloquent;
use App\Services\Criteria\CriteriaData;
use App\Services\Criteria\CriteriaDataImpl;
use App\Services\Criteria\StatementManager;
use App\Services\Criteria\StatementManagerImpl;
use Illuminate\Support\ServiceProvider;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class CriteriaServiceProvider
 * @package App\Providers
 */
class CriteriaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(
            MappableRequestCriteria::class,
            MappableRequestCriteriaEloquent::class
        );
        $this->app->bind(
            RequestCriteria::class,
            RequestCriteriaEloquent::class
        );
        $this->app->bind(
            StatementManager::class,
            StatementManagerImpl::class
        );
        $this->app->bind(
            CriteriaData::class,
            CriteriaDataImpl::class
        );
    }
}
