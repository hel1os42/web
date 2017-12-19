<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User([
            'name'     => 'admin',
            'email'    => 'test@test.com',
            'password' => '123456'
        ]);

        try {
            $user->save();
        } catch (\Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException $exception) {
            if (app('env') !== 'testing') {
                throw $exception; // ignore exception for testing environment
            }
        }
    }
}
