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
        Schema::create('ascension_material_drops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ascension_material_id');
            $table->unsignedBigInteger('living_being_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ascension_material_drops');
    }
};
