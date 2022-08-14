<?php

namespace App\Http\Controllers\Tournaments;

use App\Http\Controllers\Controller;
use App\Models\Tournaments\Tournament;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TournamentController extends Controller
{
    public function all() : View
    {
        return view('tournaments/all', [
            'tournaments' => Tournament::all()->sortBy('tournament_start'),
        ]);
    }

    public function createPage() : View
    {
        return view('tournaments/add');
    }

    public function add(
        Request $request,
        \App\Services\Tournament\Tournament $tournament,
    ) : Response
    {
        try {
            $request->validate([
                'name' => 'required',
                'tournament_start' => 'required|date|after:now'
            ]);
            $tournament->create(
                $request->request->get('name'),
                $request->request->get('tournament_start'),
                Auth::id(),
            );
            return \response()
                ->json(['res' => true])
                ->setStatusCode(Response::HTTP_CREATED)
                ->send();
        } catch(\Illuminate\Validation\ValidationException $e) {
            return \response()
                ->json(['res' => false, 'err' => view('helpers.errorsList', ['errors' => $e->validator->errors()])->render()])
                ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->send();
        }
    }

    public function get(int $id) : View
    {
        $tournament = Tournament::find($id);

        return view('tournaments.get', [
            'tournament' => $tournament,
        ]);
    }
}
