<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 21.09.17
 * Time: 3:46
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Trait HandlesRequestData
 *
 * @package App\Http\Controllers
 */
trait HandlesRequestData
{
    /**
     * @param array      $allowed relations that can be in result array
     * @param Request    $request request object
     * @param array|null $additional relations that must be in result array
     *
     * @return array
     */
    public function handleWith(array $allowed, Request $request, array $additional = []): array
    {
        $with = array_intersect($allowed, explode(',', $request->get('with', '')));
        if (count($additional) > 0) {
            $with = array_merge(array_values($with), array_values($additional));
        }
        return $with;
    }
}
