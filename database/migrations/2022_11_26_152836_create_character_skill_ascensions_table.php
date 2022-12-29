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
        Schema::create('character_skill_ascensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('character_id');
            $table->string('level');
            $table->unsignedBigInteger('talent_book_item_id');
            $table->unsignedInteger('talent_book_item_quantity');
            $table->unsignedBigInteger('char_common_item_id');
            $table->unsignedInteger('char_common_item_quantity');
            $table->unsignedBigInteger('talent_boss_item_id');
            $table->unsignedInteger('talent_boss_item_quantity');
            $table->unsignedBigInteger('reward_item_id');
            $table->unsignedInteger('reward_item_quantity');
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
        Schema::dropIfExists('character_skill_ascensions');
    }
};
