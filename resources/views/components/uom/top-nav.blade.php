<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">{{ $brand }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#uomNavbar"
                aria-controls="uomNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="uomNavbar">
            <ul class="navbar-nav me-auto">
                {{ $slot }}
            </ul>
            <div class="d-flex">
                <x-uom.user-dropdown />
            </div>
        </div>
    </div>
</nav>
