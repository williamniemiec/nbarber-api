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
        Schema::create('barber_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barber');
            $table->string('name');
            $table->float('price');
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
        Schema::dropIfExists('barber_services');
    }
};
