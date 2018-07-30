<?php

namespace OmniSynapse\WebHookService\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WebHookEvent
 * @package OmniSynapse\WebHookService\Models
 */
class WebHookEvent extends Model
{

    protected $table = 'webhook_events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'payload',
        'status_code',
        'response',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'url'         => 'string',
        'payload'     => 'array',
        'status_code' => 'integer',
        'response'    => 'string',
    ];

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @return int
     */
    public function getStatusCode(): integer
    {
        return $this->response;
    }
}
