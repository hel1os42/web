<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInviteColToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('invite_code')->nullable();
        });
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['invite_code' => $this->generateInvite()]);
        }
        Schema::table('users', function (Blueprint $table) {
            $table->string('invite_code', 191)->unique()->index()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('invite_code');
        });
    }


    private function generateInvite()
    {
        $newInvite = substr(md5(rand()), 0, 5);

        if (count(DB::table('users')->where('invite_code', $newInvite)->get())) {
            return $this->generateInvite();
        }

        return $newInvite;
    }
}
