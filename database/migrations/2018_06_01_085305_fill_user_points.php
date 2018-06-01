<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Support\Collection;

class FillUserPoints extends Migration
{

    /**
     * @var ProgressBar
     */
    private $bar;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->makeProgressBar();

        $this->bar->start();

        DB::table('users')->orderBy('id')->chunk(100, function ($users) {
            $this->processChunk($users);
        });

        $this->bar->finish();
    }

    /**
     * @return void
     */
    private function makeProgressBar()
    {
        $output     = new ConsoleOutput();
        $usersCount = DB::table('users')->count();

        $this->bar = new ProgressBar($output, $usersCount);
    }

    /**
     * @param string $userId
     * @param int $referralsCount
     * @param int $redemptionsCount
     *
     * @return void
     */
    private function updateUser(string $userId, int $referralsCount, int $redemptionsCount)
    {
        $data = [
            'referral_points'   => $referralsCount,
            'redemption_points' => $redemptionsCount,
        ];

        DB::table('users')
            ->where('id', $userId)
            ->update($data);

        $this->bar->advance();
    }

    /**
     * @param Collection $users
     *
     * @return void
     */
    private function processChunk(Collection $users)
    {
        $userIds = $users->pluck('id');

        $referralsCountQuery = '(
                SELECT COUNT(*)
                FROM users as referrals
                WHERE users.id = referrals.referrer_id
            ) as referrals_count';

        $referralsCount = DB::table('users')
            ->select('users.id', DB::raw($referralsCountQuery))
            ->whereIn('users.id', $userIds)
            ->get()
            ->keyBy('id');

        $redemptionsCountQuery = '(
                SELECT COUNT(*)
                FROM redemption as nau_redemption 
                WHERE redemption.user_id = nau_redemption.user_id
            ) as redemptions_count';

        $redemptionsCount = DB::connection('pgsql_nau')
            ->table('redemption')
            ->select('redemption.user_id as id', DB::raw($redemptionsCountQuery))
            ->whereIn('redemption.user_id', $userIds)
            ->get()
            ->keyBy('id');

        $userIds->each(function ($userId) use ($referralsCount, $redemptionsCount) {
            $userReferralsData = (array)$referralsCount->get($userId, []);
            $userReferrals     = array_get($userReferralsData, 'referrals_count', 0);

            $userRedemptionsData = (array)$redemptionsCount->get($userId, []);
            $userRedemptions     = array_get($userRedemptionsData, 'redemptions_count', 0);

            $this->updateUser($userId, $userReferrals, $userRedemptions);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $resetData = [
            'referral_points'   => 0,
            'redemption_points' => 0,
        ];

        DB::table('users')->update($resetData);
    }
}
