<form method="post" action="{{ route('tournament_add') }}">
    @csrf
    <input class="form-control" type="text" name="name" id="name" placeholder="Название">
    <input class="form-control" type="datetime-local" name="tournament_start" id="tournament_start" placeholder="Время начала турнира">
    <button class="btn btn-outline-success" type="submit">Создать турнир</button>
</form>
