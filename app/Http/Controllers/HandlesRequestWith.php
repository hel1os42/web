<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 21.09.17
 * Time: 3:46
 */

namespace App\Http\Controllers;

/**
 * Trait HandlesRequestWith
 *
 * @package App\Http\Controllers
 */
trait HandlesRequestWith
{
    /**
     * @param array      $allowed relations that can be in result array
     * @param string     $requested comma-separated list of relation
     * @param array|null $additional relations that must be in result array
     *
     * @return array
     */
    public function handleRequestWith(array $allowed, string $requested, array $additional = []): array
    {
        $with = array_intersect($allowed, explode(',', $requested));
        if (count($additional) > 0) {
            $with = array_merge(array_values($with), array_values($additional));
        }
        return $with;
    }
}
