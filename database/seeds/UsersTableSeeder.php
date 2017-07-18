<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->setName(ENV('DEF_USER_NAME'))
            ->setEmail(ENV('DEF_USER_MAIL'))
            ->setPassword(ENV('DEF_USER_PASSWORD'));
        $user->setInvite($user->generateInvite());
        $user->save();
    }
}
