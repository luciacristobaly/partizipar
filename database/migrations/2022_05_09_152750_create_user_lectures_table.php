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
        Schema::create('users_lectures', function (Blueprint $table) {
            $table->id();
            
            $table->String('lecture_id');
            $table->foreign('lecture_id')->references('id')->on('lectures');
            
            $table->String('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->boolean('isTeacher');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_lectures');
    }
};
