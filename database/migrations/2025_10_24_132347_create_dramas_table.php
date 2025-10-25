<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dramas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('synopsis');
            $table->string('poster_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->string('type')->default('drama'); // drama, movie, series
            $table->integer('episodes')->default(1);
            $table->integer('duration')->nullable(); // in minutes
            $table->string('country');
            $table->year('release_year');
            $table->date('airing_date')->nullable();
            $table->string('status')->default('completed'); // ongoing, completed, upcoming
            $table->decimal('avg_rating', 3, 1)->default(0);
            $table->integer('total_ratings')->default(0);
            $table->integer('total_views')->default(0);
            $table->string('imdb_id')->nullable()->unique();
            $table->json('tags')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dramas');
    }
};