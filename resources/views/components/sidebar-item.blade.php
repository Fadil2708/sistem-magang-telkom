@props(['route' => '', 'icon' => 'ti-circle', 'label' => ''])

@php
    $currentRoute = request()->route()?->getName() ?? '';
    
    if (str_ends_with($route, '*')) {
        $prefix = rtrim($route, '*');
        $active = str_starts_with($currentRoute, $prefix);
    } else {
        $active = $currentRoute === $route;
    }
    
    $href = $route;
    // Try to generate route if it exists
    try {
        $baseRoute = rtrim(str_replace('*', '', $route), '.');
        $href = route($baseRoute . '.index');
    } catch (\Exception $e) {
        try {
            $href = route($baseRoute);
        } catch (\Exception $e) {
            try {
                $href = route($baseRoute . '.create');
            } catch (\Exception $e) {
                $href = '#';
            }
        }
    }
@endphp

<a href="{{ $href }}"
   class="sb-nav-item {{ $active ? 'active' : '' }}"
   @click="if(window.innerWidth <= 768) sidebarOpen = false"
   {{ $attributes }}>
    @if($active)
        <span class="sb-active-bar"></span>
    @endif
    <i class="ti {{ $icon }}"></i>
    <span>{{ $label }}</span>
</a>
