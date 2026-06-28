<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'name', 'game_type', 'score', 'total', 'xp', 'stars',
        'time_sec', 'accuracy_percent',
        'level_name', 'level_emoji', 'mode_title', 'mode_emoji',
    ];

    protected $casts = [
        'score' => 'integer',
        'total' => 'integer',
        'xp' => 'integer',
        'stars' => 'integer',
        'time_sec' => 'float',
        'accuracy_percent' => 'float',
    ];

    // Tukar ke bentuk camelCase yang difahami frontend React.
    public function toApi(): array
    {
        return [
            'id' => (string) $this->id,
            'playerName' => $this->name,
            'gameType' => $this->game_type ?: 'algebra',
            'level' => $this->level_name,
            'levelEmoji' => $this->level_emoji,
            'modeTitle' => $this->mode_title,
            'modeEmoji' => $this->mode_emoji,
            'score' => $this->score,
            'total' => $this->total,
            'xp' => $this->xp,
            'stars' => $this->stars,
            'timeSeconds' => $this->time_sec,
            'accuracyPercent' => $this->accuracy_percent,
            'createdAt' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
