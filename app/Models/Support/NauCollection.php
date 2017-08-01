<?php

namespace App\Models\Support;

use App\Models\NauModels\NauModel;
use Illuminate\Support\Collection;

/**
 * Class NauCollection
 * NS: Models\Support
 */
class NauCollection extends Collection
{
    public function setOnlyVisible($visible)
    {
        return $this->map(function (NauModel $model) use ($visible) {
            return $model->setOnlyVisible($visible);
        });
    }
}
