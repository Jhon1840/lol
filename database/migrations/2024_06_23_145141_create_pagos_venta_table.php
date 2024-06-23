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
        Schema::create('pagos_venta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('venta_detalle_id')->index('venta_detalle_id_idx');
            $table->enum('tipo_pago', ['billete', 'moneda']);
            $table->decimal('valor', 10);
            $table->unsignedInteger('cantidad');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->unsignedBigInteger('caja_id')->nullable()->index('fk_pagos_venta_caja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_venta');
    }
};
