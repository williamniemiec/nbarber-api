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
        Schema::create('user_appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_barber');
            $table->dateTime('date');
            $table
                ->foreign('id_user')
                ->references('users')
                ->on('id')
                ->cascadeOnDelete();
            $table
                ->foreign('id_barber')
                ->references('barbers')
                ->on('id')
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
        Schema::table('user_appointments', function (Blueprint $table) {
            $table->dropForeign('id_user');
            $table->dropForeign('id_barber');
        });
        Schema::dropIfExists('user_appointments');
    }
};
