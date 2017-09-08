<?php

namespace App\Models\AdditionalField;

use App\Models\AdditionalField\AdditionalField;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdditionalFieldValues
 * @package App
 *
 * @property int id
 * @property int additional_field_id
 * @property string parent_id
 * @property string parent_type
 * @property string value
 */
class AdditionalFieldValues extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('database.default');

        $this->table = 'additional_field_values';

        $this->primaryKey = 'id';

        $this->casts = [
            'additional_field_id'   => 'integer',
            'parent_id'   => 'string',
            'parent_type' => 'string',
            'value'     => 'string'
        ];

        $this->fillable = [
            'additional_field_id',
            'parent_id',
            'parent_type',
            'value'
        ];
    }

    /**
     * @param int $fieldId
     * @return AdditionalFieldValues
     */
    public function setAdditionalFieldId(int $fieldId): AdditionalFieldValues
    {
        $this->additional_field_id = $fieldId;
        return $this;
    }

    /**
     * @param string $parentId
     * @return AdditionalFieldValues
     */
    public function setParentId(string $parentId): AdditionalFieldValues
    {
        $this->parent_id = $parentId;
        return $this;
    }

    /**
     * @param string $parentType
     * @return AdditionalFieldValues
     */
    public function setParentType(string $parentType): AdditionalFieldValues
    {
        $this->parent_type = $parentType;
        return $this;
    }

    /**
     * @param string $value
     * @return AdditionalFieldValues
     */
    public function setValue(string $value): AdditionalFieldValues
    {
        $this->value = $value;
        return $this;
    }
}
