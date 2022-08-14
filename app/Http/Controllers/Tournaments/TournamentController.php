<?php

namespace App\Http\Controllers\Tournaments;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

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
    ) : Response
    {
        try {
            $request->validate([
                'name' => 'required',
                'tournament_start' => 'required|date|after:now'
            ]);
            $tournament = new Tournament();
            $tournament->setAttribute('name', $request->input('name'));
            $tournament->setAttribute('tournament_start', $request->input('tournament_start'));
            $tournament->setAttribute('user_id', Auth::user()->getAuthIdentifier());
            $tournament->save();
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
}
