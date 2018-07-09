<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\NauModels\Offer;
use Illuminate\Support\Collection;

class DeactivateOffersOfDisapprovedUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $usersIds    = $this->getUsersIds();
        $accountsIds = $this->getAccountsIds($usersIds);
        $offersIds   = $this->getActiveOffersIds($accountsIds);

        foreach ($offersIds as $offerId) {
            /**
             * @var Offer $offer
             */
            $offer = Offer::query()->where('id', $offerId)->first();
            $offer->setStatus(Offer::STATUS_DEACTIVE)->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    /**
     * @return Collection
     */
    private function getUsersIds(): Collection
    {
        return DB::table('users')
            ->join('users_roles', 'users.id', 'users_roles.user_id')
            ->join('roles', 'roles.id', 'users_roles.role_id')
            ->where('roles.name', Role::ROLE_ADVERTISER)
            ->where('approved', '1')
            ->pluck('users.id');
    }

    /**
     * @param Collection $usersIds
     * @return Collection
     */
    private function getAccountsIds(Collection $usersIds): Collection
    {
        return DB::connection('pgsql_nau')->table('account')
            ->whereIn('owner_id', $usersIds)
            ->pluck('id');
    }

    /**
     * @param Collection $accountsIds
     * @return Collection
     */
    private function getActiveOffersIds(Collection $accountsIds): Collection
    {
        return DB::connection('pgsql_nau')->table('offer')
            ->whereIn('acc_id', $accountsIds)
            ->where('status', 'active')
            ->pluck('id');
    }
}
