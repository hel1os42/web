<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactRequest;
use App\Models\Currency;
use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
{
    /**
     * @return Response
     */
    public function createTransaction(): Response
    {
        return response()->render('transaction.create', [
            'amount'      => 0.0001,
            'destination' => null,
            'source'      => auth()
                ->user()
                ->getAccountFor(Currency::NAU)
                ->getAddress()
        ]);
    }

    /**
     * @param TransactRequest $request
     *
     * @return Response
     */
    public function completeTransaction(TransactRequest $request): Response
    {
        $sourceAccount      = Account::whereAddress($request->source)->firstOrFail();
        $destinationAccount = Account::whereAddress($request->destination)->firstOrFail();
        $amount             = $request->amount;
        $transaction        = new Transact();

        if (false === $sourceAccount->isEnoughBalanceFor($amount)) {
            $multiplier = $transaction->getNauMultiplier();

            return response()->error(Response::HTTP_NOT_ACCEPTABLE,
                trans('msg.transaction.balance', [
                    'balance' => sprintf('%0.' . $multiplier . 'f', $sourceAccount->getBalance()),
                ])
            );
        }

        $transaction->amount = $amount;
        $transaction->source()->associate($sourceAccount);
        $transaction->destination()->associate($destinationAccount);

        $transaction->save();

        Session::flash('message',
            null === $transaction->id ?
                trans('msg.transaction.accepted') :
                trans('msg.transaction.saved'));

        return response()->render('transaction.complete', $transaction->toArray(),
            null === $transaction->id ?
                Response::HTTP_ACCEPTED :
                Response::HTTP_CREATED,
            route('transactionComplete')
        );
    }

    /**
     * @param int $transactionId |null
     *
     * @return Response
     */
    public function listTransactions($transactionId = null): Response
    {
        $user         = auth()->user();
        $transactions = Transact::forUser($user);

        if (null === $transactionId) {
            return response()->render('transaction.list', $transactions->paginate());
        }

        $transaction = $transactions->findOrFail($transactionId);

        return response()->render('transaction.transactionInfo', $transaction->toArray());
    }
}
