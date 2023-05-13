@extends('layouts.app')

@php
    $users = \App\Models\User::where('telegram_chat_id', '!=', '')->get();
@endphp

@section('head')
    @vite(['resources/css/app.scss', 'resources/js/debug/debug_index.js'])
@endsection

@section('content')
    <div id="debug-container" class="container">
        <div class="row">
            <div class="col-sm-8 p-2">
                <div class="d-flex flex-column gap-1">
                    <pre class="bg-light h-100" style="min-height: 500px"
                         id="board">
                    </pre>
                </div>
            </div>
            <div class="col-sm-4 p-2">
                <div class="d-flex flex-column gap-1">
                    @for($i=0;$i<2;$i++)
                        <div>
                            <select name="users" id="" class="w-100 p-2" class="form-select">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                            data-chat-id="{{ $user->telegram_chat_id }}">
                                        {{ $user->login }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endfor
                    <button class="btn btn-outline-primary" data-action="stateUpdate">Обновить</button>
                    <button class="btn btn-outline-primary" data-action="prepareGame">Занять стол</button>
                    <button class="btn btn-outline-primary" data-action="start">Старт</button>
                    <button class="btn btn-outline-primary" data-action="count" data-side="red">Гол - красный</button>
                    <button class="btn btn-outline-primary" data-action="count" data-side="blue">Гол - синий</button>
                    <button class="btn btn-outline-primary" data-action="resetLastGoal">Сброс последнего гола</button>
                    <button class="btn btn-outline-primary" data-action="gameOver">Отменить игру</button>
                </div>
            </div>
        </div>
    </div>
@endsection
