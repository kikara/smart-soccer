<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\TableOccupation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TableOccupationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(['user_id' => ['required', 'integer']]);

        $tableOccupation = TableOccupation::factory()->create([
            'user_id' => $validated['user_id']
        ]);

        return response()->json([
            'table_occupation_id' => $tableOccupation->id
        ]);
    }
}
