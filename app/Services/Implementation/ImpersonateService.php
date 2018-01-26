<?php

namespace App\Services\Implementation;


use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lab404\Impersonate\Services\ImpersonateManager;
use App\Services\ImpersonateService as ImpersonateServiceInterface;

/**
 * Class ImpersonateService
 * @package App\Services\Implementation
 */
class ImpersonateService implements ImpersonateServiceInterface
{
    /**
     * @var ImpersonateManager
     */
    protected $manager;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(ImpersonateManager $manager, UserRepository $userRepository)
    {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $roles
     *
     * @return bool
     */
    protected function impersonatedBy(array $roles): bool
    {
        $impersonatorId = $this->manager->getImpersonatorId();

        try{
            $impersonator = $this->userRepository->find($impersonatorId);
            return $impersonator->hasRoles($roles);
        } catch (ModelNotFoundException $exception) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function impersonatedByAdminOrAgent(): bool
    {
        return $this->impersonatedBy([Role::ROLE_ADMIN, Role::ROLE_AGENT]);
    }
}