<?php

namespace App\Http\Controllers;

use App\Helpers\FormRequest;
use App\Http\Requests\TransactRequest;
use App\Models\Contracts\Currency;
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
    private $transactionRepository;
    private $accountRepository;

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function createTransaction(): Response
    {
        $this->authorize('createTransaction', $this->transactionRepository->model());

        return response()->render('transaction.create', FormRequest::preFilledFormRequest(TransactRequest::class, [
            'amount' => 1,
            'source' => $this->auth
                ->user()
                ->getAccountFor(Currency::NAU)
                ->getAddress()
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
        $this->authorize('completeTransaction', $this->transactionRepository->model());

        $sourceAccount      = $this->accountRepository->findByAddressOrFail($request->source);
        $destinationAccount = $this->accountRepository->findByAddressOrFail($request->destination);
        $amount             = $request->amount;

        $transaction = $this->transactionRepository
            ->createWithAmountSourceDestination($amount, $sourceAccount, $destinationAccount);

        return response()->render('transaction.complete', $transaction->toArray(),
            null === $transaction->id ?
                Response::HTTP_ACCEPTED :
                Response::HTTP_CREATED,
            route('transaction.complete')
        );
    }

    /**
     * @param int|null $transactionId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function listTransactions(int $transactionId = null): Response
    {
        $this->authorize('listTransactions', $this->transactionRepository->model());

        $user         = $this->auth->user();
        $transactions = $this->transactionRepository->getBySenderOrRecepient($user);

        if (null === $transactionId) {
            return response()->render('transaction.list', $transactions->paginate());
        }

        $transaction = $transactions->findOrFail($transactionId);

        return response()->render('transaction.transactionInfo', $transaction->toArray());
    }
}
