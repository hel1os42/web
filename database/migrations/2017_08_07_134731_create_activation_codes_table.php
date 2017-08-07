<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivationCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activation_codes', function (Blueprint $table)
        {
            $table->increments('id', 10)->unique()->unsigned();
            $table->string('code')->unique()->index();
            $table->uuid('user_id');
            $table->uuid('offer_id');
            $table->uuid('redemption_id')->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('activation_codes', function(Blueprint $table)
        {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activation_codes');
    }
}
