<?php

namespace App\Models;

use App\Models\NauModels\NauModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class UserOfferPivot
 * @package App\Models
 */
class UserOfferPivot extends Pivot
{
    /**
     * UserOfferPivot constructor.
     *
     * @param Model  $parent
     * @param array  $attributes
     * @param string $table
     * @param bool   $exists
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(
        Model $parent,
        array $attributes,
        $table,
        $exists = false
    )
    {
        $this->dateFormat = NauModel::DATE_FORMAT;

        parent::__construct($parent, $attributes, $table, $exists);
    }
}
