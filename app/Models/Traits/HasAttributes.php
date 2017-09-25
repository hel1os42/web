<?php
namespace App\Models\Traits;

trait HasAttributes
{
    public static function getFillableWithDefaults()
    {
        $model = new static();
        $array = [];

        foreach ($model->getFillable() as $item) {
            $array[$item] = $model->getAttributeValue($item);
        }

        return $array;
    }
}
