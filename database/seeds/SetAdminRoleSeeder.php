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
        $user = new User([
            'name'     => 'Admin',
            'email'    => 'sadm@nau.io',
            'password' => 'jf747hsf',
            'phone'    => '+380123456789'
        ]);

        $success = false;

        try {
            $success = $user->save();
        } catch (\Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException $exception) {
            if (app('env') !== 'testing') {
                throw $exception; // ignore exception for testing environment
            }
        }

        if($success){
            $user->roles()->attach(\App\Models\Role::findByName('admin')->getId(),
                \App\Models\Role::findByName('user')->getId());
        }
    }
}
