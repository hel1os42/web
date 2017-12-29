<?php

namespace App\Jobs;

use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class TransferNau
 * NS: App\Jobs
 */
class TransferNau implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $amount;
    public $userId;
    public $tries = 100;

    public function __construct($amount, $userId)
    {
        $this->amount = $amount;
        $this->userId = $userId;
    }

    public function handle(
        UserRepository $userRepository,
        TransactionRepository $transactionRepository,
        AccountRepository $accountRepository
    ) {
        $systemAccount = $accountRepository->findWhere([
            'owner_id' => '00000000-0000-0000-0000-000000000000'
        ], ['id'])->first();

        $user = $userRepository->find($this->userId);

        $transactionRepository
            ->createWithAmountSourceDestination($this->amount, $systemAccount, $user->getAccountForNau(), true);
    }
}
