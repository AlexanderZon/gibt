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
        Schema::create('character_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('character_id');
            $table->enum('type', ['icon', 'side_icon', 'gacha_card', 'gacha_splash', 'namecard']);
            $table->string('url');
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
        Schema::dropIfExists('character_images');
    }
};
