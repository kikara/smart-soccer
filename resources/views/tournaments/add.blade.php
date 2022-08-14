<form method="post" action="{{ route('tournament_add') }}" id="tournamentForm">
    <div id="tournamentAddErrors" class="alert alert-danger d-none"></div>
    @csrf
    <input class="form-control my-2" type="text" name="name" id="name" placeholder="Название">
    <input class="form-control my-2" type="datetime-local" name="tournament_start" id="tournament_start" placeholder="Время начала турнира">
    <button class="btn btn-outline-success w-100 my-2" id="tournamentCreateBtn">Создать турнир</button>
</form>
