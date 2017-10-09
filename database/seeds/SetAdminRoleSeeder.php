<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class SetAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::query()->where('email', 'test@test.com')->first();

        if ($user instanceof User) {
            if (class_exists('\App\Models\Role')) {
                $user->roles()->attach(\App\Models\Role::findByName('admin'));
            }
        }
    }
}
