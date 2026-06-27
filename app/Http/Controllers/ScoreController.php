<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    // GET /api/scores?limit=5  — Top N (markah tertinggi, masa terpantas, XP tertinggi)
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 10);
        $limit = max(1, min($limit, 50));

        $scores = Score::query()
            ->orderByDesc('score')
            ->orderBy('time_sec')
            ->orderByDesc('xp')
            ->limit($limit)
            ->get()
            ->map(fn (Score $s) => $s->toApi());

        return response()->json($scores);
    }

    // POST /api/scores — simpan satu rekod
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:32'],
            'score' => ['required', 'integer', 'min:0', 'max:50'],
            'total' => ['required', 'integer', 'min:1', 'max:50'],
            'xp' => ['required', 'integer', 'min:0', 'max:100000'],
            'timeSec' => ['required', 'numeric', 'min:0', 'max:100000'],
            'levelName' => ['nullable', 'string', 'max:32'],
            'levelEmoji' => ['nullable', 'string', 'max:8'],
            'modeTitle' => ['nullable', 'string', 'max:32'],
            'modeEmoji' => ['nullable', 'string', 'max:8'],
        ]);

        $score = Score::create([
            'name' => trim($data['name']),
            'score' => $data['score'],
            'total' => $data['total'],
            'xp' => $data['xp'],
            'time_sec' => $data['timeSec'],
            'level_name' => $data['levelName'] ?? null,
            'level_emoji' => $data['levelEmoji'] ?? null,
            'mode_title' => $data['modeTitle'] ?? null,
            'mode_emoji' => $data['modeEmoji'] ?? null,
        ]);

        return response()->json($score->toApi(), 201);
    }
}
