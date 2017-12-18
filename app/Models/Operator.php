<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Operator extends Model
{
    use Uuids;

    /**
     * Operators constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'operators';
        $this->primaryKey = 'id';

        $this->initUuid();

        $this->casts = [
            'place_uuid' => 'string',
            'login'      => 'string',
            'pin'        => 'string',
            'is_active'  => 'boolean',
        ];

        $this->hidden = [
            'pin',
            'remember_token',
        ];

        $this->fillable = [
            'place_uuid',
            'login',
            'pin',
            'is_active',
        ];

        $this->appends = [
            'place_uuid',
            'login',
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
     * Get operator parent place uuid
     *
     * @return string
     */
    public function getPlace_uuid(): string
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
     * Get operator status
     *
     * @return bool
     */
    public function getIs_active(): boolean
    {
        return $this->is_active();
    }

    /**
     * Get operator parent place object
     *
     * @return Place
     */
    public function place_uuid(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'place_uuid', 'id');
    }
}
