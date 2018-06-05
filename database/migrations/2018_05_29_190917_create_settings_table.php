<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;

class CreateSettingsTable extends Migration
{
    public function __construct()
    {
        if (version_compare(Application::VERSION, '5.0', '>=')) {
            $this->tablename   = Config::get('settings.table');
            $this->keyColumn   = Config::get('settings.keyColumn');
            $this->valueColumn = Config::get('settings.valueColumn');
        } else {
            $this->tablename   = Config::get('anlutro/l4-settings::table');
            $this->keyColumn   = Config::get('anlutro/l4-settings::keyColumn');
            $this->valueColumn = Config::get('anlutro/l4-settings::valueColumn');
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename, function(Blueprint $table)
        {
            $table->increments('id');
            $table->string($this->keyColumn, 191)->index();
            $table->text($this->valueColumn);
        });

        // Insert required configuration
        setting([
            'min_balance_for_change_invite' => '0',
            'min_level_for_change_invite'   => '0',
        ])->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->tablename);
    }
}
