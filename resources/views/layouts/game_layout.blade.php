<div class="container js-container">
    <div class="row text-center">
        <div class="col-sm">
            <div class="card-body" data-user="{{ $data['round']['blue_gamer_id'] }}">
                @php
                $path = $data['blue_gamer_name']['avatar_path'] ? 'storage/' . $data['blue_gamer_name']['avatar_path'] : 'storage/avatar.png'
                @endphp
                <img src="{{ asset($path) }}" alt="" class="rounded-circle" style="width: 150px; height: 150px">
                <h5 class="card-title js-blue-name">{{ $data['blue_gamer_name']['login'] }}</h5>
                <div class="mt-3" style="padding-right: 20%; padding-left: 20%">
                    <div class="card bg-primary text-white js-card-color-primary">
                        <div class="card-body">
                            <h1 class="card-title js-blue-count">{{ $data['round']['blue_count'] }}</h1>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row mt-2">
                            <div class="col-sm-4 border border-success rounded bg-success pt-1 js-round-count"
                                 {{ $data['rounds_count'][$data['round']['blue_gamer_id']] > 0 ? '' : 'hidden' }}
                            ></div>
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
            <div class="card-body" data-user="{{ $data['round']['red_gamer_id'] }}">
                @php
                    $path = $data['red_gamer_name']['avatar_path'] ? 'storage/' . $data['red_gamer_name']['avatar_path'] : 'storage/avatar.png'
                @endphp
                <img src="{{ asset($path) }}" alt="" class="rounded-circle" style="width: 150px; height: 150px">
                <h5 class="card-title js-red-name">{{ $data['red_gamer_name']['login'] }}</h5>
                <div class="mt-3" style="padding-right: 20%; padding-left: 20%">
                    <div class="card bg-danger text-white js-card-color-danger">
                        <div class="card-body">
                            <h1 class="card-title js-red-count">{{ $data['round']['red_count'] }}</h1>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row mt-2">
                            <div class="col-sm-4 border border-success rounded bg-success pt-1 js-round-count"
                                {{ $data['rounds_count'][$data['round']['red_gamer_id']] > 0 ? '' : 'hidden' }}
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

