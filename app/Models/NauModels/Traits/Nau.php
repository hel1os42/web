<?php
/**
 * web
 * ©2017 necromancer
 * Date: 8/1/17
 * Time: 18:00
 */

namespace App\Models\NauModels\Traits;


use App\Models\NauModels\NauModel;
use App\Models\Traits\HasNau;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

trait Nau
{
    use HasNau;
    use ReadOnlyTrait, Eloquence, Mappable {
        ReadOnlyTrait::save insteadof Eloquence;
    }

    public static function bootNau()
    {
        static::hook('toArray', function ($next, $value, $args) {
            /** @var NauModel $this */
            foreach (array_keys($this->getMaps()) as $key) {
                if (!in_array($key, $this->hidden)) {
                    $value[$key] = $this->mapAttribute($key);
                }
            }

            return $next($value, $args);
        });
    }

    public function setOnlyVisible($visible)
    {
        $attributes = array_merge(
            array_keys($this->getAttributes()),
            array_keys($this->getMaps())
        );

        $this->addVisible(array_diff($attributes, $this->getHidden()));
        $this->makeHidden($attributes);
        $this->makeVisible($visible);

        return $this;
    }
}