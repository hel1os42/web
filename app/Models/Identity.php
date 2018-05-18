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
     * @param mixed $userId
     * @return Identity
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * @param mixed $providerId
     * @return Identity
     */
    public function setProviderId($providerId)
    {
        $this->provider_id = $providerId;
        return $this;
    }

    /**
     * @param mixed $externalUserId
     * @return Identity
     */
    public function setExternalUserId($externalUserId)
    {
        $this->external_user_id = $externalUserId;
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
