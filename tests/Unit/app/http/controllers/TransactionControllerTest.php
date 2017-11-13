<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactRequest;
use App\Models\NauModels\Account;
use App\Models\NauModels\Transact;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\Implementation\AccountRepositoryEloquent;
use App\Repositories\Implementation\TransactionRepositoryEloquent;
use App\Repositories\TransactionRepository;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\ResponseFactory;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    /**
     * @var TransactionController
     */
    private $controller;

    /**
     * @var TransactionRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $transactionRepository;

    /**
     * @var AccountRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $accountRepository;

    /**
     * @var AuthManager|PHPUnit_Framework_MockObject_MockObject
     */
    private $authManager;

    /**
     * @var Guard|PHPUnit_Framework_MockObject_MockObject
     */
    private $guard;

    /**
     * @var User|PHPUnit_Framework_MockObject_MockObject
     */
    private $user;

    /**
     * @var Account|PHPUnit_Framework_MockObject_MockObject
     */
    private $account;

    /**
     * @var Transact|PHPUnit_Framework_MockObject_MockObject
     */
    private $transaction;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * TransactionControllerTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Faker::create();
    }

    /**
     * @before
     */
    public function before()
    {
        $this->transactionRepository = $this->getMockBuilder(TransactionRepositoryEloquent::class)->disableOriginalConstructor()->getMock();
        $this->accountRepository     = $this->getMockBuilder(AccountRepositoryEloquent::class)->disableOriginalConstructor()->getMock();
        $this->authManager           = $this->getMockBuilder(AuthManager::class)->disableOriginalConstructor()->getMock();
        $this->guard                 = $this->getMockBuilder(Guard::class)->disableOriginalConstructor()->getMock();
        $this->user                  = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $this->account               = $this->getMockBuilder(Account::class)->disableOriginalConstructor()->getMock();
        $this->transaction           = $this->getMockBuilder(Transact::class)->disableOriginalConstructor()->getMock();
        $this->authManager->method('guard')->with()->willReturn($this->guard);
        $this->guard->method('user')->with()->willReturn($this->user);
        $this->guard->method('id')->with()->willReturn($this->user->method('getId'));

        $this->configureTestUser();

        $this->configureTestAccount();

        $this->configureTestTransaction();

        $this->configureTransactionRepository();

        $this->controller = new TransactionController($this->transactionRepository, $this->accountRepository, $this->authManager);
    }

    private function configureTestUser()
    {
        $userData = [
            'id'      => $this->faker->uuid,
            'account' => $this->account
        ];

        $this->user->method('getId')->willReturn($userData['id']);
        $this->user->method('getAccountFor')->willReturn($this->account);
    }

    private function configureTestAccount()
    {
        $accountData = [
            'address' => $this->faker->uuid,
        ];

        $this->account->method('getAddress')->willReturn($accountData['address']);
    }

    private function configureTestTransaction()
    {
        $this->transaction->method('toArray')->willReturn($this->transaction);
    }

    private function configureTransactionRepository()
    {
        $transactionRepository = [
            'findByAddress' => $this->account,
        ];

        $this->accountRepository->method('findByAddressOrFail')->willReturn($transactionRepository['findByAddress']);
    }

    /**
     * @test
     *
     * @dataProvider CreateTransactionData
     *
     * @param array $data
     *
     */
    public function createTransactionTest(array $data)
    {
        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();

        $response = new Response();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

        $data['source'] = $this->account->getAddress();

        $responseFactory
            ->expects(self::once())
            ->method('__call')
            ->with('render', ['transaction.create', $data])
            ->willReturn($response);

        $returnValue = $this->controller->createTransaction();
        self::assertSame($response, $returnValue);
    }

    /**
     * @test
     *
     * @dataProvider TransactionData
     *
     * @param array $data
     *
     */
    public function listTransactionTest($data)
    {
        $builder = $this->getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $pagination = $this->getMockBuilder(LengthAwarePaginator::class)->disableOriginalConstructor()->getMock();

        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();

        $response = new Response();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

        $builder
            ->method('paginate')
            ->willReturn($pagination);

        $builder
            ->method('findOrFail')
            ->willReturn($this->transaction);

        $this->transactionRepository
            ->method('getBySenderOrRecepient')
            ->willReturn($builder);

        if ($data) {
            $responseFactory
                ->method('__call')
                ->with('render', ['transaction.transactionInfo', $this->transaction])
                ->willReturn($response);
        }
        else {
            $responseFactory
                ->method('__call')
                ->with('render', ['transaction.list', $pagination])
                ->willReturn($response);
        }

        $returnValue = $this->controller->listTransactions($data);
        self::assertSame($response, $returnValue);
    }

    /**
     * @test
     *
     * @dataProvider TransactionData
     *
     * @param array $data
     *
     */
    public function completeTransactionTest($data)
    {
        $responseFactory      = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();
        $request              = $this->getMockBuilder(TransactRequest::class)->disableOriginalConstructor()->getMock();
        $request->amount      = $this->faker->randomDigitNotNull;
        $request->sender      = $this->faker->uuid;
        $request->destination = $this->faker->uuid;

        $response = new Response();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

        $this->transasctionId = $data;

        $responseFactory
            ->expects(self::once())
            ->method('__call')
            ->with('render', ['transaction.complete', null, Response::HTTP_ACCEPTED, route('transaction.complete')])
            ->willReturn($response);

        $returnValue = $this->controller->completeTransaction($request);
        self::assertSame($response, $returnValue);
    }

    public function createTransactionData()
    {
        return [
            [
                ['source' => '', 'destination' => null, 'amount' => 1]
            ]
        ];
    }

    public function transactionData()
    {
        return [[null], [$this->faker->randomDigitNotNull]];
    }
}
