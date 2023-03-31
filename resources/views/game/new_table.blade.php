<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-6 mx-auto">
        <h1>Стол свободен</h1>
    </div>
</div>
<div>
    <h5>Последние игры</h5>
    <div class="table-responsive">
        <table class="table text-center">
            <tr>
                <th>Дата</th>
                <th>Игрок</th>
                <th>Общее время</th>
                <th>Счет</th>
            </tr>
            @foreach($games as $game)
                <tr>
                    <td>{{ $game->dateTime->format('d.m.Y H:i') }}</td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            @php $swords = true @endphp
                            @foreach($game->users as $user)
                                <span>
                                    {{ $user->login }}
                                </span>
                                @if($swords)
                                    <img src="{{ asset('images/swords.svg') }}" alt="" width="20">
                                    @php $swords = false @endphp
                                @endif
                            @endforeach
                        </div>
                    </td>
                        <?php // Общее время ?>
                    <td>
                        {{ gmdate('i:s', $game->totalTime) }}
                    </td>
                        <?php // Счет ?>
                    <td>
                        @php $counts = [] @endphp
                        @foreach($game->rounds as $round)
                            @foreach($round->scores as $score)
                                @php
                                    $is = $counts[$score->user_id] ?? 0;
                                    $counts[$score->user_id] = $score->score >= 10 ? $is + 1 : $is;
                                @endphp
                            @endforeach
                        @endforeach
                        {{ implode(' : ', $counts) }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
