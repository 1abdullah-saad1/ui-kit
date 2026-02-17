<li>
    <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#{{ $id }}" aria-expanded="false">
        @if($icon)
            <i class="{{ $icon }} me-2"></i>
        @endif
        {{ $title }}
    </button>
    <div class="collapse" id="{{ $id }}">
        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            {{ $slot }}
        </ul>
    </div>
</li>
