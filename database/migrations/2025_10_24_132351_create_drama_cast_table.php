<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDramaCastTable extends Migration
{
    public function up()
    {
        Schema::create('drama_cast', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drama_id')->constrained('dramas')->onDelete('cascade');
            $table->foreignId('cast_member_id')->constrained('cast_members')->onDelete('cascade');
            $table->string('character_name')->nullable();
            $table->string('role_type')->default('supporting'); // lead, supporting, guest
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drama_cast');
    }
}