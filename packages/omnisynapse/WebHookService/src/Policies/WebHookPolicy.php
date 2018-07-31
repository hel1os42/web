<?php

namespace OmniSynapse\WebHookService\Policies;

use App\Models\User;
use OmniSynapse\WebHookService\Models\WebHook;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebHookPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param      $ability
     *
     * @SuppressWarnings("unused")
     *
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the webHook.
     *
     * @param  \App\Models\User $user
     * @param  WebHook          $webHook
     * @return mixed
     */
    public function view(User $user, WebHook $webHook)
    {
        return $webHook->user->is($user);
    }

    /**
     * Determine whether the user can create webHooks.
     *
     * @param  \App\Models\User $user
     *
     * @SuppressWarnings("unused")
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the webHook.
     *
     * @param  \App\Models\User $user
     * @param  WebHook          $webHook
     * @return mixed
     */
    public function update(User $user, WebHook $webHook)
    {
        return $webHook->user->is($user);
    }

    /**
     * Determine whether the user can delete the webHook.
     *
     * @param  \App\Models\User $user
     * @param  WebHook          $webHook
     * @return mixed
     */
    public function delete(User $user, WebHook $webHook)
    {
        return $webHook->user->is($user);
    }
}
