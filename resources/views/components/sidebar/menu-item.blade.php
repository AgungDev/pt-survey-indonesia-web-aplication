@props(['item'])

@php
    $isActive = false;
    if (isset($item['route'])) {
        $isActive = request()->routeIs($item['route'].'*');
        if ($item['route'] === 'dashboard.index' && request()->is('dashboard')) {
            $isActive = true;
        }
    }
@endphp

@if(!empty($item['children']))
    <li class="nav-item has-treeview {{ collect($item['children'])->contains(fn($child) => request()->routeIs($child['route'].'*') || ($child['route'] === 'dashboard.index' && request()->is('dashboard'))) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ collect($item['children'])->contains(fn($child) => request()->routeIs($child['route'].'*') || ($child['route'] === 'dashboard.index' && request()->is('dashboard'))) ? 'active' : '' }}">
            <i class="nav-icon {{ $item['icon'] }}"></i>
            <p>
                {{ $item['label'] }}
                <i class="right bi bi-chevron-down"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @foreach($item['children'] as $child)
                <x-sidebar.menu-item :item="$child" />
            @endforeach
        </ul>
    </li>
@else
    <li class="nav-item">
        <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" class="nav-link {{ $isActive ? 'active' : '' }}">
            <i class="nav-icon {{ $item['icon'] }}"></i>
            <p>{{ $item['label'] }}</p>
        </a>
    </li>
@endif
