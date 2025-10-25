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
        Schema::table('comments', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['drama_id']);
            
            // Modify the column to be nullable
            $table->foreignId('drama_id')->nullable()->change()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['drama_id']);
            
            // Change back to NOT NULL
            $table->foreignId('drama_id')->nullable(false)->change()->constrained()->onDelete('cascade');
        });
    }
};
