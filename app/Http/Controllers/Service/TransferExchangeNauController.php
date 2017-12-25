<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ExchangeNau;
use App\Repositories\AccountRepository;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Response;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Exception\RequestException;
use OmniSynapse\CoreService\FailedJob\CrossChange as CrossChangeFailed;
use OmniSynapse\CoreService\Response\CrossChange as CrossChangeSuccess;

/**
 * Class TransferExchangeNauController
 * NS: App\Http\Controllers\Service
 */
class TransferExchangeNauController extends Controller
{
    public function exchangeNau(
        ExchangeNau $request,
        CoreService $coreService,
        AccountRepository $accountRepository,
        Dispatcher $eventsDispatcher
    ) {
        $address = $request->address;
        $account = $accountRepository->findByAddressOrFail($address);

        $job = $coreService->crossChange($account, $request->amount, $request->direction === 'in');

        $result = null;

        $eventsDispatcher->listen(CrossChangeSuccess::class, function (CrossChangeSuccess $crossChange) use (&$result) {
            $result = \response()->render('', $crossChange, Response::HTTP_CREATED, route('transactionList', [
                'transactionId' => $crossChange->transaction_id
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
}
