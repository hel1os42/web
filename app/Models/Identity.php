<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Identity extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'user_id',
        'identity_provider_id',
        'external_user_id',
    ];

    /**
     * @return BelongsTo
     */
    public function identityProvider(): BelongsTo
    {
        return $this->belongsTo(IdentityProvider::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param mixed $user_id
     * @return Identity
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @param mixed $provider_id
     * @return Identity
     */
    public function setProviderId($provider_id)
    {
        $this->provider_id = $provider_id;
        return $this;
    }

    /**
     * @param mixed $external_user_id
     * @return Identity
     */
    public function setExternalUserId($external_user_id)
    {
        $this->external_user_id = $external_user_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @return int
     */
    public function getProviderId(): int
    {
        return $this->provider_id;
    }

    /**
     * @return string
     */
    public function getExternalUserId(): string
    {
        return $this->external_user_id;
    }

}
