<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Attributes
 * NS: App\Helpers
 */
class Attributes
{
    public static function getFillableWithDefaults(Model $model, $without = [])
    {
        $array = [];

        foreach ($model->getFillable() as $item) {
            if (in_array($item, $without)) {
                continue;
            }

            $array[$item] = $model->getAttributeValue($item);
        }

        return $array;
    }
}
