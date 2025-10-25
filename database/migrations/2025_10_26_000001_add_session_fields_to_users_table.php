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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_active_at')->nullable()->after('last_login_at');
            $table->string('session_id')->nullable()->after('last_active_at');
            $table->ipAddress('last_ip_address')->nullable()->after('session_id');
            $table->text('user_agent')->nullable()->after('last_ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_active_at', 'session_id', 'last_ip_address', 'user_agent']);
        });
    }
};
