<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable();
            $table->string('avatar_path')->nullable();
            $table->text('bio')->nullable();
            $table->string('theme_preference')->default('light'); // light, dark
            $table->string('role')->default('user'); // admin, moderator, user
            $table->boolean('is_verified')->default(false);
            $table->json('preferences')->nullable();
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 
                'avatar_path', 
                'bio', 
                'theme_preference', 
                'role', 
                'is_verified', 
                'preferences', 
                'last_login_at'
            ]);
        });
    }
};