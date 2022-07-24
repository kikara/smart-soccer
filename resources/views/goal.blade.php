@extends('layouts.app')

@section('content')
<script>
    let conn = new WebSocket('ws://192.168.1.30:8080');
    conn.onopen = function (e) {
        console.log('Соединение установлено');
    }
    conn.onmessage = function (e) {
        console.log(e.data);
    }
    conn.onclose = function (e) {
        console.log('соединение закрыто')
    }
</script>
<div class="container">
    <h1>This is test page</h1>
</div>
@endsection
