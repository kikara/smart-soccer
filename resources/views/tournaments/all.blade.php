@extends('layouts.app')

@section('content')
    <div class="btn btn-outline-primary float-end" id="tournamentCreateForm">
        Создать турнир
    </div>

    <table class="table" id="tournamentsTable">
        <tr>
            <th></th>
            <th>Название</th>
            <th>Время начала</th>
            <th>Кол-во участников</th>
            <th>Создал</th>
            <th>Статус</th>
            <th></th>
        </tr>
        @foreach($tournaments as $tournament)
            <tr>
                <td>@include('tournaments.participationBtn')</td>
                <td>{{ $tournament->name }}</td>
                <td>{{ $tournament->tournament_start }}</td>
                <td class="playersCount">{{ $tournament->players->count() }}</td>
                <td>{{ $tournament->user->login }}</td>
                <td>{{ $tournament->status->name }}</td>
                <td>
                    <a class="btn btn-outline-success" href="{{ route('tournament_get', ['id' => $tournament->id]) }}">
                        Открыть
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
