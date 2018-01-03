<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class EditLatitudeLongitudeColLengthForAllTables2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('database.default') === 'mysql') {
            DB::statement('ALTER TABLE users MODIFY latitude DOUBLE(8,5);');
            DB::statement('ALTER TABLE users MODIFY longitude DOUBLE(8,5);');

            DB::statement('ALTER TABLE places MODIFY latitude DOUBLE(8,5);');
            DB::statement('ALTER TABLE places MODIFY longitude DOUBLE(8,5);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
