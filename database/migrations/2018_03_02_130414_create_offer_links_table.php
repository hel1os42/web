<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateOfferLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('offer_links');

        DB::beginTransaction();

        try {
            Schema::create('offer_links', function (Blueprint $table) {
                $table->increments('id');
                $table->string('tag', 191);
                $table->string('title');
                $table->text('description');
                $table->uuid('place_id');
                $table->timestamps();

                $table->foreign('place_id')->references('id')->on('places');

                $table->unique(['place_id', 'tag']);
            });
        } catch (PDOException $exc) {
            DB::rollBack();

            Schema::dropIfExists('offer_links');

            throw $exc;
        }

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_links');
    }
}
