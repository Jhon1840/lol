<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dinero_caja', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('caja_id')->index('caja_id');
            $table->enum('tipo', ['billete', 'moneda']);
            $table->decimal('denominacion', 10);
            $table->integer('cantidad');
            $table->decimal('total', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dinero_caja');
    }
};
