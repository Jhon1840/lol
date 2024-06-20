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
        Schema::table('pagos_venta', function (Blueprint $table) {
            $table->foreign(['caja_id'], 'fk_pagos_venta_caja')->references(['id'])->on('caja')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['venta_detalle_id'], 'fk_venta_detalle_pago')->references(['id'])->on('venta_detalles')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos_venta', function (Blueprint $table) {
            $table->dropForeign('fk_pagos_venta_caja');
            $table->dropForeign('fk_venta_detalle_pago');
        });
    }
};
