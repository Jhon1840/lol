<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\Permission;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permission1 = Permission::create(['name' => 'writterr']);
        $user = User::find(1);
        $user->givePermissionTo($permission1);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
