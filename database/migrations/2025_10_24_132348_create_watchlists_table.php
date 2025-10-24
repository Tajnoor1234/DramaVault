<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchlistsTable extends Migration
{
    public function up()
    {
        Schema::create('watchlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('drama_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('plan_to_watch'); // watching, completed, on_hold, dropped
            $table->timestamps();
            
            $table->unique(['user_id', 'drama_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('watchlists');
    }
}