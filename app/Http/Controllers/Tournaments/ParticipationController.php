<?php

namespace App\Http\Controllers\Tournaments;

use App\Http\Controllers\Controller;
use App\Models\Tournaments\Tournament;
use App\Services\Tournament\Participation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ParticipationController extends Controller
{
    public function add(
        Request $request,
    ) : Response
    {
        $tournamentId = $request->request->getInt('id');

        $participation = new Participation(Auth::id(), $tournamentId);
        $participation->addPlayer();

        return \response()
            ->json($this->getResultByTournament($tournamentId))
            ->setStatusCode(Response::HTTP_CREATED)
            ->send();
    }

    public function remove(
        Request $request,
    ) : Response
    {
        $tournamentId = $request->request->getInt('id');

        $participation = new Participation(Auth::id(), $tournamentId);
        $participation->removePlayer();

        return \response()
            ->json($this->getResultByTournament($tournamentId))
            ->setStatusCode(Response::HTTP_PARTIAL_CONTENT)
            ->send();
    }

    /**
     * @param int $tournamentId
     * @return array{res: bool, html: string, players: int}
     */
    private function getResultByTournament(int $tournamentId) : array
    {
        $tournament = Tournament::find($tournamentId);
        return [
            'res' => true,
            'html' => view('tournaments.participationBtn', ['tournament' => $tournament])->render(),
            'players' => $tournament->players()->count(),
        ];
    }
}
