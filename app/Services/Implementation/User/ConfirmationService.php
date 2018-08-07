<?php

namespace App\Services\Implementation\User;

use App\Mail\UserConfirmation;
use App\Models\User;
use App\Models\User\Confirmation;
use App\Repositories\User\ConfirmationRepository;
use App\Repositories\UserRepository;
use App\Services\User\ConfirmationService as ConfirmationServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

/**
 * Class ConfirmationService
 * @package App\Services\Implementation\User
 *
 */
class ConfirmationService implements ConfirmationServiceInterface
{
    protected $repository;

    /**
     * ConfirmationService constructor.
     * @param ConfirmationRepository $repository
     */
    public function __construct(ConfirmationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @return void
     * @throws \Prettus\Repository\Contracts\ValidatorException
     */
    public function make(User $user)
    {
        $token        = $this->createToken();
        $confirmation = $this->saveToken($user, $token);

        if ($confirmation instanceof Confirmation) {
            $this->sendEmail($user, route('userConfirmation', [$token]));
        }
    }

    /**
     * @param string $token
     * @return bool
     */
    public function confirm(string $token): bool
    {
        $confirmation = $this->repository->findByField('token', $token, ['id', 'user_id'])->first();

        if (null === $confirmation) {
            return false;
        }

        app(UserRepository::class)->update(['confirmed' => 1], $confirmation->user_id);

        return $this->repository->delete($confirmation->id);
    }

    /**
     * @param User $user
     */
    public function disapprove(User $user)
    {
        app(UserRepository::class)->update(['confirmed' => 0], $user->id);
    }

    /**
     * Create a new token.
     *
     * @return string
     */
    private function createToken(): string
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    /**
     * @param User $user
     * @param $token
     * @return mixed
     * @throws \Prettus\Repository\Contracts\ValidatorException
     */
    private function saveToken(User $user, $token)
    {
        return $this->repository->updateOrCreate(['user_id' => $user->id], [
            'user_id'    => $user->id,
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);
    }


    /**
     * @param User   $user
     * @param string $link
     */
    protected function sendEmail(User $user, string $link)
    {
        Mail::queue(new UserConfirmation($user, $link));
    }
}
