@extends('layouts.app')

@section('content')
    <div class="display-5">{{ $tournament->name }}</div>
    <div>Создал: {{ $tournament->user->login }}</div>
    <div>Дата проведения: {{ $tournament->tournament_start }}</div>
    <div>Количество участников: {{ $tournament->players()->count() }}</div>
@endsection
