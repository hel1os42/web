<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('place_uuid');
            $table->string('login', 60)->unique();
            $table->string('password');
            $table->boolean('is_active')->default('false');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('operators', function(Blueprint $table)
        {
            $table->foreign('place_uuid')->references('id')->on('places')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operators');
    }
}
