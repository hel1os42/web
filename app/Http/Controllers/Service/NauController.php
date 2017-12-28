<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\CreateUserRequest;
use App\Http\Requests\Service\ExchangeNau;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Response;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Exception\RequestException;
use OmniSynapse\CoreService\FailedJob\CrossChange as CrossChangeFailed;
use OmniSynapse\CoreService\Response\CrossChange as CrossChangeSuccess;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class NauController
 * NS: App\Http\Controllers\Service
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class NauController extends Controller
{
    public function exchangeNau(
        ExchangeNau $request,
        CoreService $coreService,
        AccountRepository $accountRepository,
        Dispatcher $eventsDispatcher
    ) {
        $address = $request->address;
        $account = $accountRepository->findByAddressOrFail($address);

        $job = $coreService->crossChange($account, $request->ethAddress, $request->amount,
            $request->direction === 'in');

        $result = null;

        $eventsDispatcher->listen(CrossChangeSuccess::class, function (CrossChangeSuccess $crossChange) use (&$result) {
            $result = \response()->render('', $crossChange, Response::HTTP_CREATED, route('transactionList', [
                'transactionId' => $crossChange->nau->transaction_id
            ]));
        });

        $eventsDispatcher->listen(CrossChangeFailed::class, function (CrossChangeFailed $failed) use (&$result) {
            $exception = $failed->getException();

            logger()->error($exception->getMessage(), [
                'account'     => $failed->getAccount(),
                'destination' => $failed->isIncoming() ? 'in' : 'out',
                'amount'      => $failed->getAmount()
            ]);

            if ($exception instanceof RequestException) {
                logger()->debug($exception->getRawResponse());
            }

            $result = \response()->error(Response::HTTP_INTERNAL_SERVER_ERROR, "Internal server error");
        });

        $job->handle();

        while (null === $result) {
            usleep(100);
        };

        return $result;
    }

    public function getAccount(User $user)
    {
        return \response()->render('', $user->getAccountForNau()->toArray());
    }

    /**
     * @param CreateUserRequest $request
     * @param UserRepository    $userRepository
     *
     * @return mixed
     * @throws UnprocessableEntityHttpException
     * @throws \InvalidArgumentException
     */
    public function createUser(CreateUserRequest $request, UserRepository $userRepository)
    {
        $referrerUser = User::findByInvite("NAU");
        $referrerId   = null !== $referrerUser ? $referrerUser->id : null;
        $newUserData  = [
            'referrer_id' => $referrerId,
            'email'       => $request->email,
            'phone'       => $request->phone
        ];

        $user = $userRepository->create($newUserData);

        $success = $user->exists;

        if (!$success) {
            throw new UnprocessableEntityHttpException();
        }

        return response()->render(
            '', $user->fresh(), Response::HTTP_CREATED,
            route('users.show', [$user->getId()]));
    }
}
