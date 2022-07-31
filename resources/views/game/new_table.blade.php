<div class="container js-container">
    <div class="px-4 py-5 my-5 text-center">
        <div class="col-lg-6 mx-auto">
            <h1>Стол свободен</h1>
        </div>
    </div>
    <div>
        <h5>Последние игры</h5>
        <table class="table table-bordered text-center">
            <tr>
                <th>Дата</th>
                <th>Игрок</th>
                <th>Игрок</th>
                <th>Общее время</th>
                <th>Счет</th>
            </tr>
            @foreach($games as $game)
                <tr>
                    <td>{{ $game['date'] }}</td>
                    <td>{{ $users[$game['gamerOne']]['login'] }}</td>
                    <td>{{ $users[$game['gamerTwo']]['login'] }}</td>
                    <td>{{ $game['time']['minutes'] . ' : ' . $game['time']['secs'] }}</td>
                    <td>{{ $game['accountStr'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
