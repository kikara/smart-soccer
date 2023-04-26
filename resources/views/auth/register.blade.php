@extends('layouts.app')

@section('head')
    @vite(['resources/css/app.scss'])
@endsection

@section('content')
    @extends('auth.content')

    @section('form')
        <form method="POST" action="{{ route('register') }}" class="mb-0">
            <div class="card-body d-flex flex-column gap-2">
                @csrf
                <div class="">
                    <input id="login" type="text" class="form-control @error('login') is-invalid @enderror rounded-0"
                           name="login"
                           value="{{ old('login') }}" required autocomplete="login" autofocus
                           placeholder="Введите логин"
                    >

                    @error('login')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror rounded-0"
                           placeholder="Введите пароль"
                           name="password" required autocomplete="new-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <button type="submit" class="btn btn-primary rounded-0 w-100">
                    Зарегистрироваться
                </button>
            </div>
        </form>
    @endsection
@endsection
