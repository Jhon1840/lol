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
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->foreign(['venta_id'], 'venta_detalles_ibfk_1')->references(['id'])->on('ventas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['producto_id'], 'venta_detalles_ibfk_2')->references(['id'])->on('products')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->dropForeign('venta_detalles_ibfk_1');
            $table->dropForeign('venta_detalles_ibfk_2');
        });
    }
};
