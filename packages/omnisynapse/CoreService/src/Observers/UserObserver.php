<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\User;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class UserObserver extends AbstractJobObserver
{
    /**
     * @param User $user
     *
     * @throws ServiceUnavailableHttpException
     */
    public function created(User $user)
    {
        if (false === $this->queue($this->getCoreService()->userCreated($user))) {
            try {
                $user->delete();
            } catch (\Exception $ignore) {
                logger()->error($ignore->getMessage());
            }

            throw new ServiceUnavailableHttpException(5);
        }
    }
}
