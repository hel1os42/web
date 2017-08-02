<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected function forUserOnlyFields(string $model, bool $isDbField = false)
    {
        switch ($model) {
            case 'Offer': {
                if ($isDbField) {
                    return [
                        'id',
                        'name',
                        'descr',
                        'dt_start',
                        'dt_finish',
                        'tm_start',
                        'tm_finish',
                        'country',
                        'city',
                        'categ',
                        'lat',
                        'lng',
                        'radius'
                    ];
                }
                return [
                    'id',
                    'label',
                    'description',
                    'start_date',
                    'finish_date',
                    'start_time',
                    'finish_time',
                    'country',
                    'city',
                    'category_id',
                    'latitude',
                    'longitude',
                    'radius'
                ];
            }
        }
        return [];
    }
}