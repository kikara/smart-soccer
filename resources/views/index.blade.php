@extends('layouts.app')

@section('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@900&family=Rubik+Wet+Paint&display=swap" rel="stylesheet">
@endsection

@section('content')
    <div id="app">
{{--        <game-index-component is-auth="{{ auth()->check() }}"></game-index-component>--}}
        <game-component></game-component>
    </div>
@endsection
