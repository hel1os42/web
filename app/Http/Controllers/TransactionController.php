<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactRequest;
use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use Illuminate\Http\RedirectResponse;

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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|RedirectResponse
     */
    public function completeTransaction (TransactRequest $request)
    {
        $account     = Account::on('pgsql_nau')->where('owner_id', $request->input('sender'))->firstOrFail();
        $destination = Account::on('pgsql_nau')->where('owner_id', $request->input('destination'))->firstOrFail();
        $amount      = $request->input('amount');

        if(false === $account->enoughBalance($amount)) {
            return response()
                ->redirectToRoute('transCreate')
                ->withInput()
                ->withErrors([
                    'amount' => sprintf('Your balance %0.4f NAU !', $account->getBalance())
                ]);
        }

        $transaction         = (new Transact());
        $transaction->amount = $amount;
        $transaction->source()->associate($account);
        $transaction->source()->associate($destination);

        // TODO: core service send money

        return response()->render('transact.complete', [
            'sender'      => $account,
            'destination' => $destination,
            'amount'      => $amount,
        ]);
    }
}
