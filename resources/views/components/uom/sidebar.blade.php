<aside class="offcanvas offcanvas-{{ $anchor === 'right' ? 'end' : 'start' }}" tabindex="-1" id="uomSidebar" aria-labelledby="uomSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="uomSidebarLabel">{{ $title ?? 'القائمة' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-unstyled">
            {{ $slot }}
        </ul>
    </div>
</aside>
