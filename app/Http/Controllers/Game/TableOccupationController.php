<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Resources\TableOccupationResource;
use App\Models\TableOccupation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableOccupationController extends Controller
{
    public function store(Request $request): JsonResource
    {
        $validated = $request->validate(['user_id' => ['required', 'integer']]);

        $tableOccupation = TableOccupation::factory()->create([
            'user_id' => $validated['user_id']
        ]);

        return TableOccupationResource::make($tableOccupation);
    }

    public function state(Request $request): JsonResponse
    {
        $currentDateTime = date('Y-m-d H:i:s');

        $rows = TableOccupation::where('end_game', '>=', $currentDateTime)->first();

        return $rows ? response()->json(['data' => true]) : response()->json(['data' => false]);
    }
}
