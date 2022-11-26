<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('character_id');
            $table->string('level');
            $table->float('hp', 7,2);
            $table->float('atk', 7,2);
            $table->float('def', 7,2);
            $table->float('crit_rate', 7,2);
            $table->float('crit_dmg', 7,2);
            $table->unsignedBigInteger('variable_stat_id');
            $table->float('variable_stat_value', 7,2);
            $table->unsignedBigInteger('char_jewel_id');
            $table->unsignedInteger('char_jewel_quantity');
            $table->unsignedBigInteger('char_elemental_id');
            $table->unsignedInteger('char_elemental_quantity');
            $table->unsignedBigInteger('char_local_material_id');
            $table->unsignedInteger('char_local_material_quantity');
            $table->unsignedBigInteger('char_common_item_id');
            $table->unsignedInteger('char_common_item_quantity');
            $table->unsignedInteger('mora_quantity');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_stats');
    }
};
