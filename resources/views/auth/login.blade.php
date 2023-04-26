@extends('layouts.app')

@section('head')
    @vite(['resources/css/app.scss'])
    <style>
        .card {
            box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
        }
    </style>
@endsection

@section('content')

    @extends('auth.content')

    @section('form')
        <form method="POST" action="{{ route('login') }}">
            <div class="card-body d-flex flex-column gap-2">
                @csrf
                <div class="">
                    <input id="login" type="text"
                           class="form-control @error('login') is-invalid @enderror rounded-0"
                           name="login"
                           placeholder="Логин"
                           value="{{ old('login') }}" required autocomplete="login" autofocus>

                    @error('login')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>


                <div class="">
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror rounded-0"
                           name="password"
                           placeholder="Пароль"
                           required autocomplete="current-password">

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

                <button type="submit" class="btn btn-primary w-100 rounded-0">
                    Войти
                </button>
            </div>
        </form>
    @endsection

@endsection
