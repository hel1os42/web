<?php

namespace App\Models\NauModels;

use App\Models\User as WebUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class User
 * @package App\Models\NauModels
 *
 * @property string  id
 * @property int     level
 * @property int     points
 * @property WebUser owner
 */
class User extends AbstractNauModel
{

    public function __construct(array $attributes = [])
    {
        $this->table = 'users';

        $this->primaryKey = 'id';

        $this->appends = ['points'];

        $this->setVisible([
            'level',
            'points'
        ]);

        parent::__construct($attributes);
    }

    /** @return BelongsTo */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(WebUser::class, 'id', 'id');
    }

    /** @return int */
    public function getLevel(): int
    {
        return $this->level;
    }

    /** @return int */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getPointsAttribute(): int
    {
        return $this->getLevel() * 100;
    }
}
