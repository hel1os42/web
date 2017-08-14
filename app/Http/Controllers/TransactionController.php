<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactRequest;
use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function createTransaction(): Response
    {
        return response()->render('transact.create', new Transact());
    }

    /**
     * @param TransactRequest $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function completeTransaction (TransactRequest $request): Response
    {

        $senderAccount      = Account::where('owner_id', $request->sender)->firstOrFail();
        $destinationAccount = Account::where('owner_id', $request->destination)->firstOrFail();
        $amount             = $request->amount;

        if (false === $senderAccount->isEnoughBalanceFor($amount)) {
            $multiplier = (int)config('nau.multiplier');
            return response()->error(Response::HTTP_NOT_ACCEPTABLE,
                trans('msg.transactions.balance', [
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
            Session::flash('message', trans('msg.transactions.saved'));
            return response()->render('transact.complete',
                $transaction->toArray(),
                Response::HTTP_CREATED,
                route('transComplete', $transaction->toArray())
            );
        }

        Session::flash('message', trans('msg.transactions.accepted'));
        return response()->render('transact.create',
            $transaction->toArray(),
            Response::HTTP_ACCEPTED,
            route('transCreate')
        );
    }
}
