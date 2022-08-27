<div class="accordion mt-2 js-event" id="accordionEvent" data-id="{{ $eventSound['id'] }}">
    <div class="accordion-item">
        <h2 class="accordion-header" id="head_{{ $eventSound['id'] }}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $eventSound['id'] }}" aria-expanded="false" aria-controls="collapse_{{ $eventSound['id'] }}">
                {{ $eventSound['event_name'] }}
            </button>
        </h2>
        <div id="collapse_{{ $eventSound['id'] }}" class="accordion-collapse collapse" aria-labelledby="head_{{ $eventSound['id'] }}" data-bs-parent="#accordionEvent">
            <div class="accordion-body">
                <table class="table">
                    <tbody>
                    @foreach($eventSound['decoded_parameters'] as $key => $value)
                        <tr>
                            <td>{{ $paramsInfo[$key] }}</td>
                            <td class="text-start">{{ $value }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">
                            <div class="d-flex align-items-center">
                                <div
                                    class="ps-3 pe-1 py-2 bg-primary bg-opacity-10 border border-primary rounded-start rounded-end">
                                    {{ $eventSound['audio_name'] ?? '' }}
                                </div>
                                <audio class="d-inline px-2 flex-grow-1" style="margin: 0; padding: 0" controls>
                                    <source src="{{ asset('storage/' . $eventSound['path']) }}" >
                                </audio>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <button class="btn btn-danger mt-3 js-delete-event">Удалить событие</button>
            </div>
        </div>
    </div>
</div>




