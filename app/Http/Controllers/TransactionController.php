<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactRequest;
use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use Illuminate\Http\Response;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function createTransaction()
    {
        return response()->render('transact.create');
    }

    /**
     * @param TransactRequest $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function completeTransaction (TransactRequest $request)
    {

        $senderAccount      = Account::whereOwnerId($request->sender)->firstOrFail();
        $destinationAccount = Account::whereOwnerId($request->destination)->firstOrFail();
        $amount             = $request->input('amount');

        if (false === $senderAccount->isEnoughBalanceFor($amount)) {
            return response()->error(
                Response::HTTP_NOT_ACCEPTABLE,
                trans('msg.your_balance', [
                    'balance' => sprintf('%0.4f', $senderAccount->getBalance()),
                ])
            );
        }

        $transaction         = (new Transact());
        $transaction->amount = $amount;
        $transaction->source()->associate($senderAccount);
        $transaction->destination()->associate($destinationAccount);

        //$isCreated = $transaction->save();

        return response()
            ->render('transact.complete', $transaction->toArray(), Response::HTTP_ACCEPTED)
            ->header('Location: /transactions');
    }
}
