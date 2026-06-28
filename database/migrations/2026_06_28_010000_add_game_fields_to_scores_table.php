<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ADDITIF & SELAMAT — hanya menambah kolum baharu (nullable / default),
// tidak menyentuh atau memadam data sedia ada.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            if (! Schema::hasColumn('scores', 'game_type')) {
                $table->string('game_type', 32)->default('algebra')->after('name');
            }
            if (! Schema::hasColumn('scores', 'stars')) {
                $table->unsignedTinyInteger('stars')->nullable()->after('xp');
            }
            if (! Schema::hasColumn('scores', 'accuracy_percent')) {
                $table->float('accuracy_percent')->nullable()->after('stars');
            }
        });

        // Indeks untuk tapisan game + tarikh (hari ini / minggu ini).
        Schema::table('scores', function (Blueprint $table) {
            try {
                $table->index(['game_type', 'created_at'], 'scores_game_created_idx');
            } catch (\Throwable $e) {
                // indeks mungkin sudah wujud — abaikan
            }
        });
    }

    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            try {
                $table->dropIndex('scores_game_created_idx');
            } catch (\Throwable $e) {
            }
            foreach (['game_type', 'stars', 'accuracy_percent'] as $col) {
                if (Schema::hasColumn('scores', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
