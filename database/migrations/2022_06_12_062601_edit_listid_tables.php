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
        Schema::table('meetings', function (Blueprint $table) {
            $table->String('list_id')->nullable();
        });
        
        Schema::table('lectures', function (Blueprint $table) {
            $table->String('list_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn('list_id');
        });

        Schema::table('lectures', function (Blueprint $table) {
            $table->dropColumn('list_id');
        });
    }
};
