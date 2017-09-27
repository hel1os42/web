<?php
namespace App\Models\Traits;

trait HasAttributes
{
    public static function getFillableWithDefaults($without = [])
    {
        $model = new static();
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
