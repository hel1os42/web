<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersParents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_parents', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('parent_id');
            $table->primary(['user_id', 'parent_id'])->index();
        });

        Schema::table('users_parents', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('parent_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_parents');
    }
}
