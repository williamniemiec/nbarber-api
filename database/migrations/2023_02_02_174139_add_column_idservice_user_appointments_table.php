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
        Schema::table('user_appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('id_service');
            $table
                ->foreign('id_service')
                ->references('id')
                ->on('barber_services')
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
            $table->dropColumn('id_service');
        });
    }
};
