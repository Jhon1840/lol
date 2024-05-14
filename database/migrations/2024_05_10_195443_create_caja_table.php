<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajaTable extends Migration
{
    public function up()
    {
        Schema::create('caja', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_vendedor');
            $table->decimal('dinero', 10, 2);
            $table->timestamp('fecha');
            $table->unsignedBigInteger('id_vendedor');
            $table->boolean('estado')->default(0); // 0 = cerrada, 1 = abierta
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('caja');
    }
}
