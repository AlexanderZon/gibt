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
        Schema::create('weapon_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('weapon_id');
            $table->string('level');
            $table->float('atk', 7, 2);
            $table->unsignedBigInteger('variable_stat_id');
            $table->float('variable_stat_value', 7,2);
            $table->unsignedBigInteger('weap_primary_material_id');
            $table->unsignedInteger('weap_primary_material_quantity');
            $table->unsignedBigInteger('weap_secondary_material_id');
            $table->unsignedInteger('weap_secondary_material_quantity');
            $table->unsignedBigInteger('weap_common_item_id');
            $table->unsignedInteger('weap_common_item_quantity');
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
        Schema::dropIfExists('weapon_stats');
    }
};
