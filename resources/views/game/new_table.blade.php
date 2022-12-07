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
                    <td>{{ $game->date }}</td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            @if($game->winner === $game->gamerOne)
                                <img src="{{ asset('images/crown.svg') }}" alt="winner" width="25px">
                            @endif
                            <span>{{ $game->gamerOneName }}</span>
                        </div>

                    </td>
                    <td>
                        <div class="d-flex justify-content-center">
                            @if($game->winner === $game->gamerTwo)
                                <img src="{{ asset('images/crown.svg') }}" alt="">
                            @endif
                            <span>{{ $game->gamerTwoName }}</span>
                        </div>
                    </td>
                    <td>{{ $game->totalTimeFormat }}</td>
                    <td>{{ $game->gamerOneAccount . ' : ' . $game->gamerTwoAccount }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
