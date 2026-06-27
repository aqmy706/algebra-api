<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->unsignedTinyInteger('score');
            $table->unsignedTinyInteger('total');
            $table->unsignedSmallInteger('xp');
            $table->float('time_sec');
            $table->string('level_name', 32)->nullable();
            $table->string('level_emoji', 8)->nullable();
            $table->string('mode_title', 32)->nullable();
            $table->string('mode_emoji', 8)->nullable();
            $table->timestamps();

            // Indeks untuk susunan papan pendahulu.
            $table->index(['score', 'time_sec']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
