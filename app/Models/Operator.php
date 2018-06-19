<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\Hash;

class Operator extends Model implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Uuids, Authenticatable, Authorizable;

    /**
     * Operator constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'operators';
        $this->primaryKey = 'id';

        $this->initUuid();

        $this->casts = [
            'place_uuid'        => 'string',
            'login'             => 'string',
            'password'          => 'string',
            'is_active'         => 'boolean',
            'last_logged_in_at' => 'datetime'
        ];

        $this->hidden = [
            'password',
            'remember_token',
        ];

        $this->fillable = [
            'place_uuid',
            'login',
            'password',
            'is_active',
        ];

        parent::__construct($attributes);
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get operator uuid
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get operator parent place uuid
     *
     * @return string
     */
    public function getPlaceUuid(): string
    {
        return $this->place_uuid;
    }

    /**
     * Get operator login
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     *  Gets user's last login date
     *
     * @return Carbon|null
     */
    public function getLastLoggedInAt(): ?Carbon
    {
        return $this->last_logged_in_at;
    }

    /**
     * Get operator status
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get operator parent place object
     *
     * @return BelongsTo
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'place_uuid', 'id');
    }

    /**
     * @param string $password
     *
     * @return Void
     */
    public function setPasswordAttribute(string $password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Set last logged in datetime
     *
     * @param Carbon $date
     *
     * @return
     */
    public function setLastLoggedInAt(Carbon $date): Operator
    {
        $this->last_logged_in_at = $date;

        return $this;
    }
}
