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
        Schema::table('dinero_caja', function (Blueprint $table) {
            $table->foreign(['caja_id'], 'dinero_caja_ibfk_1')->references(['id'])->on('caja')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dinero_caja', function (Blueprint $table) {
            $table->dropForeign('dinero_caja_ibfk_1');
        });
    }
};