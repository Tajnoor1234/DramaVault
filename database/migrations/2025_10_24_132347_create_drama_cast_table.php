<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('drama_cast', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drama_id')->constrained()->onDelete('cascade');
            $table->foreignId('cast_id')->constrained()->onDelete('cascade');
            $table->string('character_name');
            $table->string('role_type')->default('supporting'); // lead, supporting, guest
            $table->timestamps();

            $table->unique(['drama_id', 'cast_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('drama_cast');
    }
};