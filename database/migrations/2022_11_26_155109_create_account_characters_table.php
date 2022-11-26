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
        Schema::create('account_characters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('character_id');
            $table->unsignedBigInteger('account_weapon_id');
            $table->string('level')->default('1');
            $table->tinyInteger('constellation_level')->default(0);
            $table->tinyInteger('basic_talent_level')->default(1);
            $table->tinyInteger('elemental_talent_level')->default(1);
            $table->tinyInteger('burst_talent_level')->default(1);
            $table->tinyInteger('friendship_level')->default(1);
            $table->unsignedBigInteger('artf_flower_id')->default(0);
            $table->tinyInteger('artf_flower_level')->default(0);
            $table->unsignedBigInteger('artf_plume_id')->default(0);
            $table->tinyInteger('artf_plume_level')->default(0);
            $table->unsignedBigInteger('artf_sands_id')->default(0);
            $table->tinyInteger('artf_sands_level')->default(0);
            $table->unsignedBigInteger('artf_goblet_id')->default(0);
            $table->tinyInteger('artf_goblet_level')->default(0);
            $table->unsignedBigInteger('artf_circlet_id')->default(0);
            $table->tinyInteger('artf_circlet_level')->default(0);
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
        Schema::dropIfExists('account_characters');
    }
};
