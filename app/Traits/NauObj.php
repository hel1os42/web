<?php
namespace App\Traits;

use App\Exceptions\NauObjException;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Job\OfferCreated;
use OmniSynapse\CoreService\Job\OfferRedemption;
use OmniSynapse\CoreService\Job\OfferUpdated;
use OmniSynapse\CoreService\Job\SendNau;
use OmniSynapse\CoreService\Job\TransactionNotification;
use OmniSynapse\CoreService\Job\UserCreated;

/**
 * Trait NauObj
 * @package App\Traits
 */
trait NauObj
{
    /**
     * @param string $jobClassName
     */
    public function save(string $jobClassName){
        $coreService = app()->make(CoreService::class);

        switch ($jobClassName) {
            case OfferCreated::class;
                $coreService->offerCreated($this);
                break;

            case OfferRedemption::class;
                $coreService->offerRedemption($this);
                break;

            case OfferUpdated::class;
                $coreService->offerUpdated($this);
                break;

            case SendNau::class;
                $coreService->sendNau($this);
                break;

            case TransactionNotification::class;
                $coreService->userCreated($this);
                break;

            case UserCreated::class;
                $coreService->transactionNotification($this);
                break;

            default:
                $this->showException();
        }

        $coreService->handle();
    }

    private function showException()
    {
        throw new NauObjException(sprintf('Not allowed to persist changes in read-only model %d', get_called_class()));
    }

    private static function staticShowException()
    {
        throw new NauObjException(sprintf('Not allowed to persist changes in read-only model %d', get_called_class()));
    }

    static function create() {
        self::staticShowException();
    }

    static function update() {
        self::staticShowException();
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
