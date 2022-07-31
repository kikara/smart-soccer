@extends('layouts.app')

@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', (e) => {
            game.init();
        });
    </script>
    @include('game.new_table')
@endsection
