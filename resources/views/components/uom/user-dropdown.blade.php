<div class="dropdown">
    <a href="#" class="d-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        @if($user && method_exists($user, 'getAvatarUrl'))
            <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}" width="35" height="35" class="rounded-circle">
        @else
            <i class="fa-solid fa-user-circle fa-2x"></i>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end text-small shadow">
        @if($user)
            <li><label class="dropdown-item">{{ $user->name }}</label></li>
            <li><hr class="dropdown-divider"></li>
        @endif
        <li>
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket me-2"></i> {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
