<form id="event-add">
    @csrf
    <table class="table">
        <tbody>
        <tr>
            <td>Событие</td>
            <td>
                <select name="event" id="" class="form-select form-select-sm">
                    <option value="0">-</option>
                    @foreach($events as $event)
                        <option value="{{ $event['id'] }}">{{ $event['name'] }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td>Аудио</td>
            <td>
                <input type="file" name="sound" id="" class="form-control form-control-sm">
            </td>
        </tr>
        <tr>
            <td>Добавить параметр</td>
            <td>
                <div class="js-param">
                    <div class="d-flex py-2 js-param-container">
                        <div class="">
                            <select name="param" id="" class="form-select form-select-sm">
                                <option value="0">-</option>
                                @foreach($parameters as $parameter)
                                    <option value="{{ $parameter['code'] }}">{{ $parameter['description'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="px-2 w-25">
                            <input type="text" class="form-control form-control-sm" name="param_value">
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary btn-sm js-add-param" type="button">Добавить</button>
            </td>
        </tr>
        </tbody>
    </table>
</form>

