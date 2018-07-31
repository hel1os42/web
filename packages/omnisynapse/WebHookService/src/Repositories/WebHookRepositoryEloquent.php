<?php

namespace OmniSynapse\WebHookService\Repositories;

use OmniSynapse\WebHookService\Models\WebHook;
use OmniSynapse\WebHookService\Presenters\WebHookPresenter;
use OmniSynapse\WebHookService\Repositories\Contracts\WebHookRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

class WebHookRepositoryEloquent extends BaseRepository implements WebHookRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WebHook::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
