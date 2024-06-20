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
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('fecha')->useCurrent();
            $table->decimal('total', 15);
            $table->string('cliente', 100)->nullable();
            $table->string('metodo_pago', 50)->nullable();
            $table->string('estado', 50)->nullable();
            $table->timestamps();
            $table->text('vendedor')->nullable();
            $table->decimal('cambio', 10)->nullable()->default(0);
            $table->unsignedBigInteger('caja_id')->nullable()->index('fk_ventas_caja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
