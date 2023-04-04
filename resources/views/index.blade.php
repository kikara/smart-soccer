@extends('layouts.app')

@section('head')
    <script src="{{ asset('js/vuetify.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Wet+Paint&family=Teko:wght@700&display=swap" rel="stylesheet">
@endsection

@section('content')
    <div id="app">
        <game-index-component is-auth="{{ auth()->check() }}"></game-index-component>
    </div>
@endsection
