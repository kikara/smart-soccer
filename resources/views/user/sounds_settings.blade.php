@extends('layouts.app')

@section('content')
    <div class="container js-container">
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let soundSettings = new SoundSetting();
                soundSettings.init();
            });
        </script>
        <div class="text-center">
            <div>
                @php
                    $path = Auth::user()->avatar_path ? 'storage/' . Auth::user()->avatar_path : 'storage/avatar.png'
                @endphp
                <img src="{{ asset($path) }}" alt="" style="width: 80px" class="rounded-circle">
                <h2 class="mt-3">{{ Auth::user()->login }}</h2>
            </div>
        </div>
        <div class="row mt-5">
            @include('user.layouts.nav')
            <div class="col-sm">
                <div class="card">
                    <div class="card-header">Добавить звук на событие</div>
                    <div class="card-body">

                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">Добавить звук на гол</div>
                    <div class="card-body js-single-audio-container">
                        <form id="audio-single-goal">
                            <div class="input-group">
                                @csrf
                                <input type="file" class="form-control" id="inputGroupFile04"
                                       aria-describedby="inputGroupFileAddon04" aria-label="Upload" name="single-sound"
                                        accept=".mp3,audio/*">
                                <button class="btn btn-outline-secondary js-sound-upload" type="button"
                                        id="inputGroupFileAddon04">
                                    Загрузить
                                </button>
                            </div>
                        </form>
                        @foreach($userSounds as $sound)
                            @include('user.layouts.audio_container')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
