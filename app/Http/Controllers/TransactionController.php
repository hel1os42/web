<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactRequest;
use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Models\Currency;

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
        $transaction                    = new Transact;
        $transaction->source_account_id = auth()
            ->user()
            ->getAccountFor(Currency::NAU)
            ->getAddress();
        return response()->render('transaction.create', $transaction);
    }

    /**
     * @param TransactRequest $request
     * @return Response
     */
    public function completeTransaction(TransactRequest $request): Response
    {
        $senderAccount      = Account::whereAddress($request->sender)->firstOrFail();
        $destinationAccount = Account::whereAddress($request->destination)->firstOrFail();
        $amount             = $request->amount;

        if (false === $senderAccount->isEnoughBalanceFor($amount)) {
            $multiplier = (int)config('nau.multiplier');
            return response()->error(Response::HTTP_NOT_ACCEPTABLE,
                trans('msg.transaction.balance', [
                    'balance' => sprintf('%0.'.$multiplier.'f', $senderAccount->getBalance()),
                ])
            );
        }

        $transaction         = (new Transact());
        $transaction->amount = $amount;
        $transaction->source()->associate($senderAccount);
        $transaction->destination()->associate($destinationAccount);

        $transaction->save();

        Session::flash('message',
            null === $transaction->id ?
                trans('msg.transaction.accepted') :
                trans('msg.transaction.saved'));

        return response()->render('transaction.complete', [
            'transaction' => $transaction
        ],
            null === $transaction->id ?
                Response::HTTP_ACCEPTED :
                Response::HTTP_CREATED,
            route('transactionComplete')
        );
    }

    /**
     * @param int $transactionId|null
     * @return Response
     */
    public function listTransactions($transactionId = null): Response
    {
        $user         = auth()->user();
        $transactions = Transact::forUser($user);

        if (null === $transactionId) {
            if (request()->wantsJson()) {
                $transactions = $transactions->get();
            }

            return response()->render('transaction.list', [
                'transactions' => $transactions
            ]);
        }

        $transaction = $transactions->findOrFail($transactionId);
        return response()->render('transaction.transactionInfo', [
            'transaction' => $transaction
        ]);
    }
}
