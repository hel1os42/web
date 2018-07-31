<?php

namespace OmniSynapse\WebHookService\Repositories;

use OmniSynapse\WebHookService\Models\WebHookEvent;
use OmniSynapse\WebHookService\Repositories\Contracts\WebHookEventRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class WebHookEventRepositoryEloquent extends BaseRepository implements WebHookEventRepository
{
    protected $fieldSearchable = [
        'user_id',
        'url',
        'status_code',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WebHookEvent::class;
    }
}
