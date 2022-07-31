<div class="container js-container">
    <div class="row text-center">
        <div class="col-sm">
            <div class="card-body">
                <img src="{{ asset('img/avatar.png') }}" alt="" class="rounded" style="width: 180px">
                <h5 class="card-title js-blue-name">{{ $data['blue_gamer_name']['name'] }}</h5>
                <div class="mt-3" style="padding-right: 20%; padding-left: 20%">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h1 class="card-title js-blue-count">{{ $data['blue'] }}</h1>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-white text-white">-</button>
                            <button type="button" class="btn btn-white text-white">+</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-2">
            <div class="card-body">
                <div class="mt-3" style="">
                    <h1 class="js-time">0:0</h1>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card-body">
                <img src="{{ asset('img/avatar.png') }}" alt="" class="rounded" style="width: 180px">
                <h5 class="card-title js-red-name">{{ $data['red_gamer_name']['name'] }}</h5>
                <div class="mt-3" style="padding-right: 20%; padding-left: 20%">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h1 class="card-title js-red-count">{{ $data['red'] }}</h1>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-white text-white">-</button>
                            <button type="button" class="btn btn-white text-white">+</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center w-100">
        <button class="btn btn-success js-reset" style="width: 200px">Сброс</button>
    </div>
</div>

