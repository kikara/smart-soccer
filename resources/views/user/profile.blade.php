@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center">
        <div>
            @php
            $path = Auth::user()->avatar_path ? 'storage/' . Auth::user()->avatar_path : 'storage/avatar.png'
            @endphp
            <img src="{{ asset($path) }}" alt="" style="width: 80px" class="rounded-circle">
            <h2 class="mt-3">{{ Auth::user()->login }}</h2>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-sm-3 mb-3">
            <div class="card">
                <div class="card-header">Детали</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item active"><a href="" class="card-link nav-link">Общие настройки</a></li>
                    <li class="list-group-item"><a href="" class="card-link nav-link">Команды</a></li>
                    <li class="list-group-item"><a href="" class="card-link nav-link">Последние игры</a></li>
                </ul>
            </div>
        </div>
        <div class="col-sm">
            <div class="card">
                <div class="card-header">Настройки пользователя</div>
                <div class="card-body">
                    <form method="POST" action="/saveProfile" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Логин" value="{{ Auth::user()->login }}" name="login">
                        </div>
                        <div class="form-group mt-3">
                            <input type="text" class="form-control" placeholder="Имя" value="{{ Auth::user()->name }}" name="name">
                        </div>

                        <div class="form-group mt-3">
                            <input type="email" class="form-control" placeholder="email@example.com" value="{{ Auth::user()->email }}" name="email">
                        </div>

                        <div class="mt-3">
                            <input class="form-control" type="file" id="formFile" name="avatar">
                        </div>

                        <div class="d-flex mt-3">
                            @if (! empty(Auth::user()->telegram_nickname))
                                <div class="">
                                    <div class="card">
                                        <div class="card-body"  style="padding: 8px">
                                            <img src="https://telegram.org/img/favicon-32x32.png" alt="" class="">
                                            @ {{ Auth::user()->telegram_nickname }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                                <div class="form-group col-3 d-flex align-items-center">
                                    <a href="https://telegram.me/SmartSoccerBot?start={{ Auth::user()->telegram_token }}"
                                       class="btn btn-primary ms-3" target="__blank">
                                        Привязать Telegram
                                    </a>
                                </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-success mt-5">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
