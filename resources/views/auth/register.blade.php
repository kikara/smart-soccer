@extends('layouts.app')

@section('head')
    @vite(['resources/css/app.scss'])
@endsection

@section('content')
    @extends('auth.content')

    @section('form')
        <form method="POST" action="{{ route('register') }}" class="mb-0">
            <h1 class="fw-normal h3 text-center mt-3">
                Регистрация
            </h1>
            <div class="card-body d-flex flex-column gap-2">
                @csrf
                <div class="form-floating">
                    <input id="login" type="text" class="form-control @error('login') is-invalid @enderror rounded-0"
                           name="login"
                           value="{{ old('login') }}" required autocomplete="login" autofocus
                           placeholder="Введите логин"
                    >
                    <label for="login">Логин</label>

                    @error('login')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-floating">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror rounded-0"
                           placeholder="Введите пароль"
                           name="password" required autocomplete="new-password">

                    <label for="password">Пароль</label>

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <button type="submit" class="btn btn-lg btn-primary rounded-0 w-100">
                    Зарегистрироваться
                </button>
                <a class="btn btn-primary rounded-0 w-100 btn-lg" href="/login">
                    Войти
                </a>
            </div>
        </form>
    @endsection
@endsection
