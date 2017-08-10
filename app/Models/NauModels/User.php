<?php

namespace App\Models\NauModels;

use App\Models\User as WebUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * Class User
 * @package App
 *
 * @property string id
 * @property int level
 * @property int points
 */
class User extends NauModel
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'users';

        $this->primaryKey = 'id';

        $this->appends = ['points'];

        $this->setVisible([
            'level',
            'points'
        ]);

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
        return $this->getLevel()*100;
    }
}
