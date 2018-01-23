<?php

namespace App\Http\Controllers;

use App\Helpers\FormRequest;
use App\Http\Requests\TransactRequest;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
{
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * TransactionController constructor.
     *
     * @param TransactionRepository $transactionRepository
     * @param AccountRepository     $accountRepository
     * @param AuthManager           $authManager
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        TransactionRepository $transactionRepository,
        AccountRepository $accountRepository,
        AuthManager $authManager
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository     = $accountRepository;

        parent::__construct($authManager);
    }


    /**
     * @return Response
     * @throws \App\Exceptions\TokenException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function createTransaction(): Response
    {
        $sourceAccount = $this->user()->getAccountForNau();

        $this->authorize('transactions.create', $sourceAccount);

        return response()->render('transaction.create', FormRequest::preFilledFormRequest(TransactRequest::class, [
            'amount' => 1,
            'source' => $sourceAccount->getAddress(),
        ]));
    }

    /**
     * @param TransactRequest $request
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function completeTransaction(TransactRequest $request): Response
    {
        $sourceAccount      = $this->accountRepository->findByAddressOrFail($request->source);
        $destinationAccount = $this->accountRepository->findByAddressOrFail($request->destination);
        $amount             = $request->amount;
        $noFee              = false;
        $authorizeAbility   = 'transactions.create';

        if ($request->has('no_fee')) {
            $noFee            = true;
            $authorizeAbility = $authorizeAbility . '.no_fee';
        }

        $this->authorize($authorizeAbility, [$sourceAccount, $destinationAccount]);

        $transaction = $this->transactionRepository
            ->createWithAmountSourceDestination($amount, $sourceAccount, $destinationAccount, $noFee);

        return response()->render(
            null === $transaction->id
                ? 'transaction.in-progress'
                : 'transaction.complete',
            $transaction->toArray(),
            null === $transaction->id
                ? Response::HTTP_ACCEPTED
                : Response::HTTP_CREATED,
            route('transaction.transactionList')
        );
    }

    /**
     * @param string|null $transactionId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function listTransactions(string $transactionId = null): Response
    {
        $this->authorize('transactions.list', $this->user());

        $transactions = $this->transactionRepository->getBySenderOrRecepient($this->user());

        if (null === $transactionId) {
            return response()->render('transaction.list', $transactions->paginate());
        }

        $transaction = $transactions->findOrFail($transactionId);

        $this->authorize('transaction.show', $transaction);

        return response()->render('transaction.transactionInfo', $transaction->toArray());
    }
}
