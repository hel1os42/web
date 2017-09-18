<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneLatitudeLongitudeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
        });
        $users = DB::table('users')->get();
        $i = 0;
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['phone' => '+38093000000' . (string)$i]);
            $i++;
        }
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->unique()->index()->change();
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
            $table->dropColumn('phone');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
