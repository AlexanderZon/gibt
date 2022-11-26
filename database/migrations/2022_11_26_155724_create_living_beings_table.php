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
        Schema::create('living_beings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('living_being_class_id');
            $table->unsignedBigInteger('living_being_grade_id');
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('order');
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
        Schema::dropIfExists('living_beings');
    }
};
