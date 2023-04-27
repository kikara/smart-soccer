@extends('layouts.app')

@section('head')
    @vite(['resources/css/app.scss'])
@endsection

@section('content')

    @extends('auth.content')

    @section('form')
        <form method="POST" action="{{ route('login') }}">
            <h1 class="fw-normal h3 text-center mt-3">
                Войти
            </h1>
            <div class="card-body d-flex flex-column gap-2">
                @csrf
                <div class="form-floating">
                    <input id="login" type="text"
                           class="form-control @error('login') is-invalid @enderror rounded-0"
                           name="login"
                           placeholder="Логин"
                           value="{{ old('login') }}" required autocomplete="login" autofocus>
                    <label for="login">Логин</label>
                    @error('login')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>


                <div class="form-floating">
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror rounded-0"
                           name="password"
                           placeholder="Пароль"
                           required autocomplete="current-password">
                    <label for="password">Пароль</label>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember"
                           id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        Запомни меня
                    </label>
                </div>

                <button type="submit" class="btn btn-lg btn-primary w-100 rounded-0">
                    Войти
                </button>
                <a class="btn btn-primary btn-lg w-100 rounded-0" href="/register">
                    Регистрация
                </a>
            </div>
        </form>
    @endsection

@endsection
