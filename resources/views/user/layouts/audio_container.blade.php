
<div class="d-flex align-items-center mt-2 js-audio-container" data-id="{{ $sound['id'] }}">
    <div
        class="ps-3 pe-1 py-2 bg-primary bg-opacity-10 border border-primary rounded-start rounded-end">
        {{ $sound['name'] ?? '' }}
        <button class="d-inline btn btn-close js-delete-audio"></button>
    </div>
    <audio class="d-inline px-2 flex-grow-1" style="margin: 0; padding: 0" controls>
        <source src="{{ asset('storage/' . $sound['path']) }}">
    </audio>
</div>
