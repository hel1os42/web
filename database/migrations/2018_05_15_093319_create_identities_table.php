<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identities', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->unsignedInteger('identity_provider_id');
            $table->string('external_user_id', 191);
            $table->timestamps();

            $table->unique(['user_id', 'identity_provider_id'], 'user_providers_idx');
            $table->unique(['identity_provider_id', 'external_user_id'], 'identity_idx');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('identities');
    }
}
