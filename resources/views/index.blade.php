@extends('layouts.app')

@section('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Wet+Paint&family=Teko:wght@700&display=swap" rel="stylesheet">
@endsection

@section('content')
    <div id="app">
        <app is-auth="{{ auth()->check() }}" user-id="{{ auth()->check() ? auth()->user()->id : 0 }}"></app>
    </div>
@endsection
