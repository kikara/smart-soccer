@extends('layouts.app')

@section('content')
    <script>
        conn = new WebSocket('ws://192.168.133.86:8080');
        conn.onopen = function (e) {
            console.log('Соединение установлено')
        }
        conn.onmessage = function (e) {
            console.log(e.data)
        }
    </script>
    <div class="container js-container">
        <div class="px-4 py-5 my-5 text-center">
            <div class="col-lg-6 mx-auto">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                </div>
            </div>
        </div>
    </div>
@endsection
