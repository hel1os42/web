<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('about')->nullable();
            $table->string('address')->nullable();
            $table->float('latitude');
            $table->float('longitude');
            $table->integer('radius');
            $table->integer('stars')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->timestamps();
        });

        Schema::table('places', function(Blueprint $table)
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
        Schema::dropIfExists('places');
    }
}
