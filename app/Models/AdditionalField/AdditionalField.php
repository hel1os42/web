<?php

namespace App\Models\AdditionalField;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdditionalField
 * @package App
 *
 * @property int id
 * @property string name
 * @property string short_name
 * @property string parent_type
 * @property int reward
 * @method AdditionalField findByShortName(string $shortName)
 * @method Builder forParent(string $parent)
 */
class AdditionalField extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('database.default');

        $this->table = 'additional_fields';

        $this->primaryKey = 'id';

        $this->casts = [
            'name'        => 'string',
            'short_name'  => 'string',
            'parent_type' => 'string',
            'reward'      => 'integer'
        ];

        $this->fillable = [
            'name',
            'short_name',
            'parent_type',
            'reward'
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->short_name;
    }


    /**
     * @return int
     */
    public function getReward(): int
    {
        return $this->reward;
    }

    /**
     * @param string $name
     * @return AdditionalField
     */
    public function setName(string $name): AdditionalField
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $shortName
     * @return AdditionalField
     */
    public function setShortName(string $shortName): AdditionalField
    {
        $this->short_name = $shortName;
        return $this;
    }

    /**
     * @param int $reward
     * @return AdditionalField
     */
    public function setReward(int $reward): AdditionalField
    {
        $this->reward = $reward;
        return $this;
    }

    /**
     * @param Builder $builder
     * @param string $parent
     * @return Builder
     */
    public function scopeForParent(Builder $builder, string $parent): Builder
    {
        return $builder->where('parent_type', $parent);
    }
}
