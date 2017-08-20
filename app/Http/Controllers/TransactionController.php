<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactRequest;
use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

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
        return response()->render('transaction.create', new Transact());
    }

    /**
     * @param TransactRequest $request
     * @return Response
     */
    public function completeTransaction (TransactRequest $request): Response
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

        if ($transaction->id) {
            Session::flash('message', trans('msg.transaction.saved'));
            return response()->render('transaction.complete',
                $transaction->toArray(),
                Response::HTTP_CREATED,
                route('transactionComplete')
            );
        }

        Session::flash('message', trans('msg.transaction.accepted'));
        return response()->render('transaction.complete',
            $transaction->toArray(),
            Response::HTTP_ACCEPTED,
            route('transactionComplete')
        );
    }
}
