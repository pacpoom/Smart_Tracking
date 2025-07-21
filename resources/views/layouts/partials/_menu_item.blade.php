{{-- This is a dropdown parent --}}
@if(!$item->children->isEmpty())
<li class="nav-item">
    <a data-bs-toggle="collapse" href="#submenu-{{ $item->id }}" class="nav-link text-white {{ $item->isActive ? 'active' : '' }}" aria-controls="submenu-{{ $item->id }}" role="button" aria-expanded="{{ $item->isActive ? 'true' : 'false' }}">
        @if($level == 0)
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded">{{ $item->icon }}</i>
            </div>
            <span class="nav-link-text ms-1">{{ $item->title }}</span>
        @else
            <span class="sidenav-mini-icon"> {{ strtoupper(substr($item->title, 0, 1)) }} </span>
            <span class="sidenav-normal ms-2 ps-1"> {{ $item->title }} </span>
        @endif
    </a>
    <div class="collapse {{ $item->isActive ? 'show' : '' }}" id="submenu-{{ $item->id }}">
        <ul class="nav nav-sm flex-column">
            @foreach($item->children as $child)
                {{-- Recursive call --}}
                @include('layouts.partials._menu_item', ['item' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    </div>
</li>
@else
{{-- This is a simple link --}}
<li class="nav-item">
    <a class="nav-link text-white {{ $item->isActive ? ($level == 0 ? 'active bg-gradient-dark' : 'active') : '' }}" href="{{ ($item->route && \Illuminate\Support\Facades\Route::has($item->route)) ? route($item->route) : '#' }}">
        @if($level == 0)
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-symbols-rounded">{{ $item->icon }}</i>
            </div>
            <span class="nav-link-text ms-1">{{ $item->title }}</span>
        @else
            <span class="sidenav-mini-icon"> {{ strtoupper(substr($item->title, 0, 1)) }} </span>
            <span class="sidenav-normal ms-2 ps-1"> {{ $item->title }} </span>
        @endif
    </a>
</li>
@endif
