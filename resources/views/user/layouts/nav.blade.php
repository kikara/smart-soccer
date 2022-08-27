<div class="col-sm-3 mb-3">
    <div class="card">
        <div class="card-header">Детали</div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item {{ request()->is('profile') ? 'active' : '' }}">
                <a href="{{ route('profile') }}" class="card-link nav-link">Общие настройки</a>
            </li>
            <li class="list-group-item {{ request()->is('profile/sound_settings') ? 'active' : '' }}">
                <a href="{{ route('sound_settings') }}" class="card-link nav-link">Настройки звуков</a>
            </li>
            <li class="list-group-item"><a href="" class="card-link nav-link">Команды</a></li>
            <li class="list-group-item"><a href="" class="card-link nav-link">Последние игры</a></li>
        </ul>
    </div>
</div>
