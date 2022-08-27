@extends('layouts.app')

@section('content')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            conn = new WebSocket(WS_HOST);
            conn.onopen = function (e) {
                console.log('Соединение установлено');
            }
            conn.onmessage = function (e) {
                console.log(e.data);
            }
        });
    </script>
    <div class="container js-container">
        <div class="px-4 py-5 my-5 text-center">
            <div class="col-lg-6 mx-auto">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <h1>hello world</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
