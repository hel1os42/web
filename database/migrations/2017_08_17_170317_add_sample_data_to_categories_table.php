<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class AddSampleDataToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        for ($i = 1; $i <= 3; $i++) {
            DB::table('categories')->insert([
                'id'         => Uuid::generate(),
                'name'       => 'Category ' . (string)$i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        for ($i = 1; $i <= 3; $i++) {
            DB::table('categories')->where('name', 'Category ' . (string)$i)->delete();
        }
    }
}
