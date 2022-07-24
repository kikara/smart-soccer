@extends('layouts.app')

@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', (e) => {
            game.init();
        });
    </script>
    <div class="container js-container">
        <div class="px-4 py-5 my-5 text-center">
            <div class="col-lg-6 mx-auto">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <button type="button" class="btn btn-primary btn-lg px-4 gap-3 js-start">Начать игру</button>
                </div>
            </div>
        </div>
    </div>
@endsection
