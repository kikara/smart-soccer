@extends('layouts.app')

@section('content')
    <div class="btn btn-outline-primary float-end" id="tournamentCreate">
        Создать турнир
    </div>

    <table class="table">
        <tr>
            <th>Название</th>
            <th>Время начала</th>
            <th>Создал</th>
        </tr>
        @foreach($tournaments as $tournament)
            <tr>
                <td>{{ $tournament->name }}</td>
                <td>{{ $tournament->tournament_start }}</td>
                <td>{{ $tournament->creator->login }}</td>
            </tr>
        @endforeach
    </table>
@endsection
