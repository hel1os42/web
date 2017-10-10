<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Class Role
 * @package App\Models
 *
 * @property string id
 * @property string name
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Collection users
 *
 *
 */
class Role extends Model
{
    use Uuids;

    const ROLE_USER             = 'user';
    const ROLE_ADVERTISER       = 'advertiser';
    const ROLE_CHIEF_ADVERTISER = 'chief_advertiser';
    const ROLE_AGENT            = 'agent';
    const ROLE_ADMIN            = 'admin';

    /**
     * Category constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'roles';
        $this->primaryKey = 'id';

        $this->initUuid();

        $this->casts = [
            'name' => 'string'
        ];

        parent::__construct($attributes);
    }

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }

    public static function findByName(string $name): Role
    {
        return self::query()->where('name', $name)->firstOrFail();
    }

    /** @return BelongsToMany */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_roles');
    }
}
