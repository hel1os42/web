<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

trait FractalToIlluminatePagination
{
    /**
     * @param array $fractalPagination
     *
     * @return LengthAwarePaginator
     */
    public function getIlluminatePagination(array $fractalPagination): LengthAwarePaginator
    {
        return app()->makeWith(LengthAwarePaginator::class, [
            'items'       => array_get($fractalPagination, 'data'),
            'total'       => array_get($fractalPagination, 'meta.pagination.total'),
            'perPage'     => array_get($fractalPagination, 'meta.pagination.per_page'),
            'currentPage' => array_get($fractalPagination, 'meta.pagination.current_page'),
            'options'     => [
                'path' => Paginator::resolveCurrentPath(),
            ],
        ]);
    }
}
