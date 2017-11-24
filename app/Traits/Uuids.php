<?php

namespace App\Traits;

use Webpatser\Uuid\Uuid;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function bootUuids()
    {
        static::saving(function ($model) {
            /** @var Uuids $model */
            if (null !== $model->primaryKey) {
                return;
            }

            $model->primaryKey = (string)Uuid::generate(4);
        });
    }

    protected function initUuid()
    {
        $this->incrementing = false;
        $this->keyType      = 'string';
    }
}
