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
        Schema::create('user_favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_barber');
            $table
                ->foreign('id_user')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table
                ->foreign('id_barber')
                ->references('id')
                ->on('barbers')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_favorites', function (Blueprint $table) {
            $table->dropForeign('id_user');
            $table->dropForeign('id_barber');
        });
        Schema::dropIfExists('user_favorites');
    }
};
