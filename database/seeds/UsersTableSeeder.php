<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            ->setPassword(Hash::make(ENV('DEF_USER_PASSWORD')));
        $user->save();
    }
}
