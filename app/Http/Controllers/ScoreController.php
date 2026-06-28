<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    // GET /api/scores?limit=5&game=algebra|izzan|all&period=today|week|all
    public function index(Request $request)
    {
        $limit = max(1, min((int) $request->query('limit', 20), 100));
        $game = $request->query('game', 'all');
        $period = $request->query('period', 'all');

        $query = Score::query();

        if (in_array($game, ['algebra', 'izzan'], true)) {
            $query->where('game_type', $game);
        }

        if ($period === 'today') {
            $query->where('created_at', '>=', now()->startOfDay());
        } elseif ($period === 'week') {
            $query->where('created_at', '>=', now()->startOfWeek());
        }

        $scores = $query
            ->orderByDesc('score')
            ->orderBy('time_sec')
            ->orderByDesc('xp')
            ->limit($limit)
            ->get()
            ->values()
            ->map(function (Score $s, int $i) {
                $row = $s->toApi();
                $row['rank'] = $i + 1; // kedudukan dalam senarai tertapis
                return $row;
            });

        return response()->json($scores);
    }

    // POST /api/scores — simpan satu rekod (terima format baharu & lama)
    public function store(Request $request)
    {
        // Sokong kunci baharu (playerName/timeSeconds/level) & lama (name/timeSec/levelName).
        $payload = [
            'playerName' => $request->input('playerName', $request->input('name')),
            'gameType' => $request->input('gameType', 'algebra'),
            'level' => $request->input('level', $request->input('levelName')),
            'score' => $request->input('score'),
            'total' => $request->input('total'),
            'xp' => $request->input('xp'),
            'stars' => $request->input('stars'),
            'timeSeconds' => $request->input('timeSeconds', $request->input('timeSec')),
            'accuracyPercent' => $request->input('accuracyPercent'),
            'levelEmoji' => $request->input('levelEmoji'),
            'modeTitle' => $request->input('modeTitle'),
            'modeEmoji' => $request->input('modeEmoji'),
        ];

        $validator = validator($payload, [
            'playerName' => ['required', 'string', 'max:32'],
            'gameType' => ['required', 'string', 'max:32'],
            'level' => ['nullable', 'string', 'max:32'],
            'score' => ['required', 'integer', 'min:0', 'max:1000'],
            'total' => ['required', 'integer', 'min:1', 'max:1000'],
            'xp' => ['required', 'integer', 'min:0', 'max:1000000'],
            'stars' => ['nullable', 'integer', 'min:0', 'max:3'],
            'timeSeconds' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'accuracyPercent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'levelEmoji' => ['nullable', 'string', 'max:8'],
            'modeTitle' => ['nullable', 'string', 'max:32'],
            'modeEmoji' => ['nullable', 'string', 'max:8'],
        ]);

        $data = $validator->validate();

        $score = Score::create([
            'name' => trim($data['playerName']),
            'game_type' => $data['gameType'],
            'level_name' => $data['level'] ?? null,
            'score' => $data['score'],
            'total' => $data['total'],
            'xp' => $data['xp'],
            'stars' => $data['stars'] ?? null,
            'time_sec' => $data['timeSeconds'],
            'accuracy_percent' => $data['accuracyPercent'] ?? null,
            'level_emoji' => $data['levelEmoji'] ?? null,
            'mode_title' => $data['modeTitle'] ?? null,
            'mode_emoji' => $data['modeEmoji'] ?? null,
        ]);

        return response()->json($score->toApi(), 201);
    }
}
