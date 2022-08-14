@if($tournament)
    @if($tournament->players()->find(Auth::user()))
        <a class="acceptInvitation btn btn-success" href="{{ route('participation_remove') }}" data-id="{{ $tournament->id }}">Отказаться</a>
    @else
        <a class="cancelInvitation btn btn-outline-success" href="{{ route('participation_add') }}" data-id="{{ $tournament->id }}">Участвовать</a>
    @endif
@endif
