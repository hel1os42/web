<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\TransactionController;
use App\Http\Requests\TransactRequest;
use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Faker\Factory as Faker;
use App\Models\User;

class TransactionControllerTest extends TestCase
{
    /** @var string $accountAddress */
    private $accountAddress = 'address';

    public function testCreateTransaction()
    {
        $responseMock = \Mockery::mock(Response::class);
        $responseMock->shouldReceive('render')->andReturn($responseMock);

        app()->singleton(ResponseFactory::class, function() use ($responseMock) {
            return $responseMock;
        });

        $authMock = \Mockery::mock(AuthFactory::class);
        $authMock->shouldReceive('user')->andReturn($authMock);
        $authMock->shouldReceive('getAccountFor')->andReturn($authMock);
        $authMock->shouldReceive('getAddress')->andReturn($this->accountAddress);

        app()->singleton(AuthFactory::class, function() use ($authMock) {
            return $authMock;
        });

        $createTransaction = (new TransactionController())
            ->createTransaction();

        $this->assertInstanceOf(get_class($responseMock), $createTransaction);
    }

    public function testTransactionInfo()
    {
        $responseMock = \Mockery::mock(Response::class);
        $responseMock->shouldReceive('render')->andReturn($responseMock);

        app()->singleton(ResponseFactory::class, function() use ($responseMock) {
            return $responseMock;
        });

        $authUser = \Mockery::mock(User::class);
        $authMock = \Mockery::mock(AuthFactory::class);
        $authMock->shouldReceive('user')->andReturn($authUser);

        app()->singleton(AuthFactory::class, function() use ($authMock) {
            return $authMock;
        });

        $transactions = \Mockery::mock(Transact::class);
        $transactions->shouldReceive('paginate')->andReturn($transactions);
        $transactions->shouldReceive('toArray')->andReturn([]);
        $transactions->shouldReceive('findOrFail')->andReturn($transactions);

        // list
        $createTransaction = (new TransactionController())
            ->listTransactions(null, $transactions);

        $this->assertInstanceOf(get_class($responseMock), $createTransaction);

        // one item
        $createTransaction = (new TransactionController())
            ->listTransactions(1, $transactions);

        $this->assertInstanceOf(get_class($responseMock), $createTransaction);
    }

    public function testCompleteTransaction()
    {
        $faker = Faker::create();
        $sourceAccountBalance = $faker->randomFloat();

        $sourceAccount = \Mockery::mock(Account::class);
        $sourceAccount->shouldReceive('isEnoughBalanceFor')->andReturn(true);
        $sourceAccount->shouldReceive('getBalance')->andReturn($sourceAccountBalance);

        $destinationAccount = \Mockery::mock(Account::class);

        $belongsToMock = \Mockery::mock(BelongsTo::class);
        $belongsToMock->shouldReceive('associate')->andReturn($belongsToMock);

        $transactSourceAccountId = $faker->randomDigitNotNull;
        $transactDestinationAccountId = $faker->randomDigitNotNull;
        $transactAmount = $faker->randomFloat();

        $transaction = \Mockery::mock(Transact::class);
        $transaction->shouldReceive('getId')->andReturn('');
        $transaction->shouldReceive('source')->andReturn($belongsToMock);
        $transaction->shouldReceive('destination')->andReturn($belongsToMock);
        $transaction->shouldReceive('save')->andReturn(true);
        $transaction->shouldReceive('getAttribute')->andReturn(true);
        $transaction->shouldReceive('setAttribute')->andReturn(true);
        $transaction->shouldReceive('getSourceAccountId')->andReturn($transactSourceAccountId);
        $transaction->shouldReceive('getDestinationAccountId')->andReturn($transactDestinationAccountId);
        $transaction->shouldReceive('getAmount')->andReturn($transactAmount);
        $transaction->shouldReceive('toArray')->andReturn([
            'source_account_id' => $transaction->getSourceAccountId(),
            'destination_account_id' => $transaction->getDestinationAccountId(),
            'amount' => $transaction->getAmount(),
        ]);

        $transactRequest = new TransactRequest();
        $transactRequest->amount = $faker->randomFloat();
        $transactRequest->destination = $faker->randomDigitNotNull();
        $transactRequest->source = $faker->randomDigitNotNull();

        // transaction accepted

        $controllerResponse = (new TransactionController)
            ->completeTransaction($transactRequest, $sourceAccount, $destinationAccount, $transaction);

        $testResponse = new TestResponse($controllerResponse);
        $testResponse->assertStatus(201);
        $testResponse->assertViewHas('source_account_id', $transactSourceAccountId);
        $testResponse->assertViewHas('destination_account_id', $transactDestinationAccountId);
        $testResponse->assertViewHas('amount', $transactAmount);

        // transaction created

        $transaction->shouldReceive('getId')->andReturn($faker->uuid);

        $controllerResponse = (new TransactionController)
            ->completeTransaction($transactRequest, $sourceAccount, $destinationAccount, $transaction);

        $testResponse = new TestResponse($controllerResponse);
        $testResponse->assertStatus(201);
        $testResponse->assertViewHas('source_account_id', $transactSourceAccountId);
        $testResponse->assertViewHas('destination_account_id', $transactDestinationAccountId);
        $testResponse->assertViewHas('amount', $transactAmount);
    }

    public function testFailedEnoughBalance()
    {
        $faker = Faker::create();
        $sourceAccountBalance = $faker->randomFloat();
        $sourceAccount = \Mockery::mock(Account::class);
        $sourceAccount->shouldReceive('isEnoughBalanceFor')->andReturn(false);
        $sourceAccount->shouldReceive('getBalance')->andReturn($sourceAccountBalance);
        $destinationAccount = \Mockery::mock(Account::class);
        $transactRequest = new TransactRequest();
        $transactRequest['amount'] = 0.0001;

        $controllerResponse = (new TransactionController)
            ->completeTransaction($transactRequest, $sourceAccount, $destinationAccount);

        $testResponse = new TestResponse($controllerResponse);
        $testResponse->assertStatus(406);
        $testResponse->assertViewHas('Your balance '.$sourceAccountBalance.' NAU!');
    }
}
