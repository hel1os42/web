<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\Builder;

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
        foreach($users as $user){
            $user->invite_code = $this->generateInvite();
        }
        Schema::table('users', function (Blueprint $table) {
            $table->string('invite_code')->unique()->index()->change();
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
        $newInvite = substr(uniqid(), 0, rand(3, 8));
        if(count(DB::table('users')->where('invite_code', $newInvite)->get())){
            return $this->generateInvite();
        }
        return $newInvite;
    }
}
