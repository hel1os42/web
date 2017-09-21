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
            } catch (\Exception $ignored) {
            }

            throw new ServiceUnavailableHttpException(5);
        }
    }
}
