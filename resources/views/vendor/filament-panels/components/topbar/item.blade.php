@props([
    'active' => false,
    'activeIcon' => null,
    'badge' => null,
    'badgeColor' => null,
    'badgeTooltip' => null,
    'icon' => null,
    'shouldOpenUrlInNewTab' => false,
    'url' => null,
])

@php
    $tag = $url ? 'a' : 'button';
@endphp

<li @class([
    'fi-topbar-item',
    'fi-active' => $active,
])>
    <{{ $tag }}
        @if ($url)
            {{ \Filament\Support\generate_href_html($url, $shouldOpenUrlInNewTab) }}
        @else
            type="button"
        @endif
        @class([
            'fi-topbar-item-btn',
            'bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border-blue-200 dark:border-blue-700' => $active,
            'hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 dark:hover:from-blue-900/10 dark:hover:to-purple-900/10 transition-all duration-200' => !$active,
        ])
    >
        @if ($icon || $activeIcon)
            <div @class([
                'fi-topbar-item-icon-wrapper',
                'bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-lg p-1' => $active,
            ])>
                {{ \Filament\Support\generate_icon_html(($active && $activeIcon) ? $activeIcon : $icon, attributes: (new \Illuminate\View\ComponentAttributeBag)->class(['fi-topbar-item-icon'])) }}
            </div>
        @endif

        <span @class([
            'fi-topbar-item-label',
            'font-semibold text-blue-700 dark:text-blue-300' => $active,
            'text-gray-700 dark:text-gray-300' => !$active,
        ])>
            {{ $slot }}
        </span>

        @if (filled($badge))
            <x-filament::badge
                :color="$badgeColor"
                size="sm"
                :tooltip="$badgeTooltip"
                class="bg-gradient-to-r from-red-500 to-pink-500 text-white"
            >
                {{ $badge }}
            </x-filament::badge>
        @endif

        @if (! $url)
            {{ \Filament\Support\generate_icon_html('heroicon-o-chevron-down', alias: \Filament\View\PanelsIconAlias::TOPBAR_GROUP_TOGGLE_BUTTON, attributes: (new \Illuminate\View\ComponentAttributeBag)->class(['fi-topbar-group-toggle-icon'])) }}
        @endif
    </{{ $tag }}>
</li>
