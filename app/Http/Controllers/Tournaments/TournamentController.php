<?php

namespace App\Http\Controllers\Tournaments;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function all() : View
    {
        return view('tournaments/all', [
            'tournaments' => Tournament::all(),
        ]);
    }

    public function createPage() : View
    {
        return view('tournaments/add');
    }

    public function add(Request $request)
    {
        $request->validate([

        ]);
        dd($request->query);
    }
}
