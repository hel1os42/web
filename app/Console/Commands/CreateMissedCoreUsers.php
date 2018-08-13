<?php

namespace App\Console\Commands;

use App\Exceptions\TokenException;
use App\Models\NauModels\Account;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use OmniSynapse\CoreService\CoreService;

class CreateMissedCoreUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Created missed core users';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var int
     */
    private $chunkSize = 100;

    /**
     * @var int
     */
    private $brokenUsersCount = 0;

    /**
     * @var int
     */
    private $totalUsersCount = 0;

    /**
     * @var CoreService
     */
    private $coreService;

    /**
     * Create a new command instance.
     *
     * @param UserRepository $userRepository
     * @param CoreService    $coreService
     */
    public function __construct(UserRepository $userRepository, CoreService $coreService)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->coreService    = $coreService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->totalUsersCount = $this->userRepository->count();

        $bar = $this->output->createProgressBar($this->totalUsersCount);

        $this->userRepository->chunk($this->chunkSize, function (Collection $users) use ($bar) {
            foreach ($users as $user) {
                $this->handleUser($user);
                $bar->advance();
            }
        });

        $bar->finish();

        $this->info(PHP_EOL);
        $this->info(sprintf('Total users -  %1$d', $this->totalUsersCount));
        $this->info(sprintf('Broken users -  %1$d', $this->brokenUsersCount));
    }

    /**
     * @param User $user
     */
    private function handleUser(User $user)
    {
        try {
            $account = $user->getAccountForNau();
        } catch (TokenException $exception) {
            $account = null;
        }

        if (false === $account instanceof Account) {
            $this->brokenUsersCount++;

            dispatch($this->coreService->userCreated($user));
        }
    }
}
