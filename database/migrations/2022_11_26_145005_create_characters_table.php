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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('occupation');
            $table->tinyInteger('rarity');
            $table->unsignedBigInteger('element_id');
            $table->unsignedBigInteger('vision_id');
            $table->unsignedBigInteger('weapon_type_id');
            $table->unsignedBigInteger('association_id');
            $table->tinyInteger('day_of_birth');
            $table->tinyInteger('month_of_birth');
            $table->string('constellation');
            $table->text('description');
            $table->boolean('released');
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
        Schema::dropIfExists('characters');
    }
};
