<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactRequest;
use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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
        $transaction = new Transact;
        $transaction->id = Account::where('owner_id', Auth::id())->firstOrFail()->getAddress();
        return response()->render('transaction.create', $transaction);
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
}
