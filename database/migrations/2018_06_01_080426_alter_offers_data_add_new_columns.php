<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersDataAddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers_data', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('referral_points_price')->default(0);
            $table->unsignedInteger('redemption_points_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers_data', function (Blueprint $table) {
            $table->dropColumn([
                'is_featured',
                'referral_points_price',
                'redemption_points_price',
            ]);
        });
    }
}
