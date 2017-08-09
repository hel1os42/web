<?php
namespace App\Traits;

use App\Exceptions\NauObjException;

/**
 * Trait NauObj
 * @package App\Traits
 */
trait NauObj
{
    /**
     * @param array $attributes
     */
    static function create(array $attributes = []){
        // TODO: call CoreService
    }

    /**
     * @param array $options
     */
    public function save(array $options = []){
        // TODO: call CoreService
    }

    /**
     * @param array $attributes
     * @param array $options
     */
    public function update(array $attributes = [], array $options = []){
        // TODO: call CoreService
    }

    private function showException()
    {
        throw new NauObjException(sprintf('Not allowed to persist changes in read-only model %d', get_called_class()));
    }

    private static function staticShowException()
    {
        throw new NauObjException(sprintf('Not allowed to persist changes in read-only model %d', get_called_class()));
    }

    static function forceCreate(){
        self::staticShowException();
    }

    static function firstOrCreate(){
        self::staticShowException();
    }

    static function firstOrNew(){
        self::staticShowException();
    }

    static function destroy(){
        self::staticShowException();
    }

    public function delete(){
        $this->showException();
    }

    public function restore(){
        $this->showException();
    }

    public function forceDelete(){
        $this->showException();
    }

    public function performDeleteOnModel(){
        $this->showException();
    }

    public function push(){
        $this->showException();
    }

    public function finishSave(){
        $this->showException();
    }

    public function performUpdate(){
        $this->showException();
    }

    public function touch(){
        $this->showException();
    }

    public function insert(){
        $this->showException();
    }

    public function truncate(){
        $this->showException();
    }
}
