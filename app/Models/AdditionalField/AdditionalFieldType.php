<?php

namespace App\Models\AdditionalField;

use App\Models\AdditionalField;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdditionalFieldType
 * @package App
 *
 * @property int id
 * @property string name
 * @property string short_name
 * @property string parent_type
 * @property int reward
 */
class AdditionalFieldType extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('database.default');

        $this->table = 'additional_field_types';

        $this->primaryKey = 'id';

        $this->casts = [
            'name'       => 'string',
            'short_name' => 'string',
            'reward'     => 'integer'
        ];

        $this->fillable = [
            'name',
            'short_name',
            'reward'
        ];
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'parent');
    }

    public function fields()
    {
        return $this->hasMany(AdditionalField::class, 'type_id', 'id');
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
     * @return AdditionalFieldType
     */
    public function setName(string $name): AdditionalFieldType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $shortName
     * @return AdditionalFieldType
     */
    public function setShortName(string $shortName): AdditionalFieldType
    {
        $this->short_name = $shortName;
        return $this;
    }

    /**
     * @param int $reward
     * @return AdditionalFieldType
     */
    public function setReward(int $reward): AdditionalFieldType
    {
        $this->reward = $reward;
        return $this;
    }
}
