<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'name', 'score', 'total', 'xp', 'time_sec',
        'level_name', 'level_emoji', 'mode_title', 'mode_emoji',
    ];

    protected $casts = [
        'score' => 'integer',
        'total' => 'integer',
        'xp' => 'integer',
        'time_sec' => 'float',
    ];

    // Tukar ke bentuk camelCase yang difahami frontend React.
    public function toApi(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'score' => $this->score,
            'total' => $this->total,
            'xp' => $this->xp,
            'timeSec' => $this->time_sec,
            'levelName' => $this->level_name,
            'levelEmoji' => $this->level_emoji,
            'modeTitle' => $this->mode_title,
            'modeEmoji' => $this->mode_emoji,
        ];
    }
}
