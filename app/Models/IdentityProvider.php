<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class IdentityProvider extends Model implements Transformable
{
    use TransformableTrait;

    public const PROVIDER_FACEBOOK  = 'facebook';
    public const PROVIDER_VK        = 'vk';
    public const PROVIDER_INSTAGRAM = 'instagram';
    public const PROVIDER_TWITTER   = 'twitter';

    protected $fillable = [
        'alias',
        'name',
    ];

    protected $casts = [
        'alias' => 'string',
        'name'  => 'string',
    ];

    public $timestamps = false;

    /**
     * @param string $alias
     *
     * @return IdentityProvider
     */
    public function setAlias(string $alias): IdentityProvider
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return IdentityProvider
     */
    public function setName(string $name): IdentityProvider
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
