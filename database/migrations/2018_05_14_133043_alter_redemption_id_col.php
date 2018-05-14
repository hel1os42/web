<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRedemptionIdCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            $table->index('redemption_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            $table->dropIndex('activation_codes_redemption_id_index');
        });
    }
}
