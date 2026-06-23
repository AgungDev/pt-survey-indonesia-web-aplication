<aside class="app-sidebar shadow elevation-4 {{ $theme['sidebar_class'] ?? 'sidebar-dark-primary' }}" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard.index') }}" class="brand-link">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="{{ config('app.name', 'Survey Management') }} logo"
                class="brand-image opacity-75 shadow"
                style="height:32px; width:auto;"
            />
            <span class="brand-text fw-light">{{ config('app.name', 'Survey Management') }}</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                aria-label="Main navigation"
                data-accordion="false"
                id="navigation"
            >
                @foreach($menus as $item)
                    <x-sidebar.menu-item :item="$item" />
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
