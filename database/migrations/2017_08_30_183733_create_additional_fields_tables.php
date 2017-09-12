<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateAdditionalFieldsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_fields', function (Blueprint $table) {
            $table->increments('id', 10)->unique()->unsigned();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('parent_type');
            $table->integer('reward');
            $table->timestamps();
        });

        Schema::create('additional_field_values', function (Blueprint $table) {
            $table->increments('id', 10)->unique()->unsigned();
            $table->integer('additional_field_id');
            $table->string('parent_id')->index();
            $table->string('parent_type');
            $table->string('value');
            $table->timestamps();
        });

        DB::table('additional_fields')->insert([
            $this->createFieldData('Age', 'age', 'users', 5),
            $this->createFieldData('Gender', 'gender', 'users', 5),
            $this->createFieldData('Income', 'income', 'users', 9),
            $this->createFieldData('Facebook', 'social_fb', 'users', 3),
            $this->createFieldData('Twitter', 'social_twitter', 'users', 3),
            $this->createFieldData('Instagram', 'social_instagram', 'users', 3)
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_field_values');
        Schema::dropIfExists('additional_fields');
    }

    public function createFieldData(string $name, string $shortName, string $parent, int $reward)
    {
        return [
            'name'        => $name,
            'short_name'  => $shortName,
            'reward'      => $reward,
            'parent_type' => $parent,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now()
        ];
    }
}
