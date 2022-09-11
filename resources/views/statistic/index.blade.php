@extends('layouts.app')

@section('content')
    <div>
        <table class="table table-bordered text-center">
            <thead>
            <tr>
                <td>ID</td>
                <td>Игрок</td>
                <td>Кол-во побед</td>
                <td>Кол-во поражений</td>
                <td>Кол-во игр</td>
                <td>Кол-во забитых голов</td>
                <td>Кол-во пропущенных голов</td>
                <td>Общее время</td>
                <td>Рейтинг</td>
            </tr>
            </thead>
            <tbody>
            @foreach($tableData as $data)
                <tr>
                    <td>{{ $data['id'] }}</td>
                    <td>{{ $data['login'] }}</td>
                    <td>{{ $data['win_count'] }}</td>
                    <td>{{ $data['lose_count'] }}</td>
                    <td>{{ $data['count_games'] }}</td>
                    <td>{{ $data['count_goals'] }}</td>
                    <td>{{ $data['missed_goals'] }}</td>
                    <td>{{ $data['total_time'] }}</td>
                    <td>{{ $data['rating'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
