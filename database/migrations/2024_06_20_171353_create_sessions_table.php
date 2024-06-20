<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary()->collation('utf8mb4_unicode_ci');
            $table->unsignedBigInteger('user_id');
            $table->string('ip_address', 45)->nullable()->collation('utf8mb4_unicode_ci');
            $table->text('user_agent')->nullable()->collation('utf8mb4_unicode_ci');
            $table->longText('payload')->collation('utf8mb4_unicode_ci');
            $table->integer('last_activity');

            $table->index('user_id', 'sessions_user_id_foreign');
            $table->index('last_activity', 'sessions_last_activity_index');

            $table->foreign('user_id')->references('id_usuario')->on('usuarios')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
