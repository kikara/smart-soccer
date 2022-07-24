<div class="container">
    <form>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <div class="btn-group btn-group-lg" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="type" id="btnradio1" value="1" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="btnradio1">1x1</label>

                <input type="radio" class="btn-check" name="type" id="btnradio2" value="2" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio2">2x2</label>
            </div>
        </div>
        <div>

        </div>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-3">
            <div>
                <div class="p-1 mb-2 bg-primary"></div>
                <select name="blue-gamer" class="form-select" aria-label="Default select example">
                    <option selected value="0">Выберите игрока</option>
                    @foreach($users as $user)
                        <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <div class="p-1 mb-2 bg-danger"></div>
                <select name="red-gamer" class="form-select" aria-label="Default select example">
                    <option selected value="0">Выберите игрока</option>
                    @foreach($users as $user)
                        <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
