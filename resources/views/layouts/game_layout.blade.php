<div class="container js-container">
    <div class="row text-center">
        <div class="col-sm">
            <div class="card-body">
                <img src="{{ asset('img/avatar.png') }}" alt="" class="rounded" style="width: 180px">
                <h5 class="card-title js-blue-name">{{ $data['blue_gamer_name']['login'] }}</h5>
                <div class="mt-3" style="padding-right: 20%; padding-left: 20%">
                    <div class="card bg-primary text-white js-card-color-primary">
                        <div class="card-body">
                            <h1 class="card-title js-blue-count">{{ $data['round']['blue_count'] }}</h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="card-body">
                <div class="mt-3" style="">
                    <h1 class="js-time">0:0</h1>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card-body">
                <img src="{{ asset('img/avatar.png') }}" alt="" class="rounded" style="width: 180px">
                <h5 class="card-title js-red-name">{{ $data['red_gamer_name']['login'] }}</h5>
                <div class="mt-3" style="padding-right: 20%; padding-left: 20%">
                    <div class="card bg-danger text-white js-card-color-danger">
                        <div class="card-body">
                            <h1 class="card-title js-red-count">{{ $data['round']['red_count'] }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row js-audio-container">
        <audio src="/audio/round_1.mp3" class="js-audio" type="audio/mpeg">
        </audio>
    </div>
</div>

