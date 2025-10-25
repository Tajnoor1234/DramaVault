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
            // Add username column if it doesn't exist
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }
            
            // Add last_login_at column if it doesn't exist
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('updated_at');
            }
            
            // Add role column if it doesn't exist (we already have is_admin and is_moderator, but we need role too)
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('is_moderator');
            }
            
            // Add avatar_path if it doesn't exist (we have avatar)
            if (!Schema::hasColumn('users', 'avatar_path')) {
                $table->string('avatar_path')->nullable()->after('avatar');
            }
            
            // Add theme_preference if it doesn't exist (we have theme)
            if (!Schema::hasColumn('users', 'theme_preference')) {
                $table->string('theme_preference')->default('light')->after('theme');
            }
            
            // Add is_verified if it doesn't exist
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('email_verified_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'last_login_at', 'role', 'avatar_path', 'theme_preference', 'is_verified']);
        });
    }
};
