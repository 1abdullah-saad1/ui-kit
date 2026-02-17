@php($url = $route ? route($route) : '#')
<li>
    <a class="link-dark d-flex align-items-center" href="{{ $url }}">
        @if($icon)
            <i class="{{ $icon }} me-2"></i>
        @endif
        <span>{{ $title }}</span>
    </a>
</li>
