<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = $this->getAffectedColumns();

            foreach ($columns as $column) {
                $table->unsignedInteger($column)->default(0);
            }
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
            $table->dropColumn($this->getAffectedColumns());
        });
    }

    /**
     * @return array
     */
    private function getAffectedColumns(): array
    {
        return [
            'referral_points',
            'redemption_points',
        ];
    }
}
