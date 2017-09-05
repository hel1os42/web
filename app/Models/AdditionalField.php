<?php

namespace App\Models;

use App\Models\AdditionalField\AdditionalFieldType;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdditionalField
 * @package App
 *
 * @property int id
 * @property int type_id
 * @property string user_id
 * @property string parent_id
 * @property string value
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
            'type_id'   => 'integer',
            'user_id'   => 'string',
            'parent_id' => 'string',
            'value'     => 'string'
        ];

        $this->fillable = [
            'name',
            'short_name',
            'parent_type',
            'reward',
        ];
    }

    public function type()
    {
        $this->hasOne(AdditionalFieldType::class, 'id', 'type_id');
    }

    /**
     * @return int
     */
    public function getTypeId(): int
    {
        return $this->type_id;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getParentId(): string
    {
        return $this->parent_id;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param int $typeId
     * @return AdditionalField
     */
    public function setTypeId(int $typeId): AdditionalField
    {
        $this->type_id = $typeId;
        return $this;
    }

    /**
     * @param int $userId
     * @return AdditionalField
     */
    public function setUserId(int $userId): AdditionalField
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * @param int $parentId
     * @return AdditionalField
     */
    public function setParentId(int $parentId): AdditionalField
    {
        $this->parent_id = $parentId;
        return $this;
    }

    /**
     * @param int $value
     * @return AdditionalField
     */
    public function setValue(int $value): AdditionalField
    {
        $this->value = $value;
        return $this;
    }

}
