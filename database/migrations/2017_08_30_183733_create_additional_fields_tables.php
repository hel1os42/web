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
        Schema::create('additional_field_types', function (Blueprint $table) {
            $table->increments('id', 10)->unique()->unsigned();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->integer('reward');
            $table->timestamps();
        });

        Schema::create('additional_fields', function (Blueprint $table) {
            $table->increments('id', 10)->unique()->unsigned();
            $table->integer('additional_field_type_id');
            $table->string('additional_field_id')->index();
            $table->string('additional_field_type');
            $table->string('value');
            $table->timestamps();
        });

        DB::table('additional_field_types')->insert([
            $this->createFieldData('Age', 'age', 5),
            $this->createFieldData('Male', 'male', 5),
            $this->createFieldData('Income', 'income', 9),
            $this->createFieldData('Facebook', 'social_fb', 3),
            $this->createFieldData('Twitter', 'social_twitter', 3),
            $this->createFieldData('Instagram', 'social_instagram', 3)
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_fields');
        Schema::dropIfExists('additional_field_types');
    }

    public function createFieldData(string $name, string $shortName, int $reward)
    {
        return [
            'name'       => $name,
            'short_name' => $shortName,
            'reward'     => $reward,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }
}
