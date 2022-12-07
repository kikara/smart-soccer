@extends('layouts.app')

@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', e => (new Debug()).init());
    </script>
    <style>
        div>button {width: 200px}
    </style>
    <div class="js-container container">
        <div class="d-flex flex-column gap-2 flex-wrap align-items-center">
            <button class="btn btn-primary js-prepare-game">Занять стол</button>
            <button class="btn btn-primary js-start">Старт</button>
            <button class="btn btn-primary js-blue">Синий</button>
            <button class="btn btn-primary js-red">Красный</button>
            <button class="btn btn-primary js-done">Завершить</button>
            <button class="btn btn-primary js-reset">Сброс</button>
        </div>
    </div>
@endsection
