@extends('layouts.app')

@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', (e) => {
            let debug = new Debug();
            debug.init();
        });
    </script>
    <h1>Отладочная страница</h1>
    <div class="js-container">
        <button class="btn btn-secondary js-connect">Попытка Соединения</button>
        <button class="btn btn-primary js-prepare-game">Занять стол</button>
        <button class="btn btn-primary js-start">Старт</button>
        <button class="btn btn-primary js-blue">Синий</button>
        <button class="btn btn-primary js-red">Красный</button>
        <button class="btn btn-primary js-test">9 голов за Синего</button>
    </div>
@endsection
