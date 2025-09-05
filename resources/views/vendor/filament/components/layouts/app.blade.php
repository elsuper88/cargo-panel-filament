<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}"
    @class([
        'fi min-h-screen',
        'dark' => filament()->hasDarkModeForced(),
    ])
>
    <head>
        {{ \Filament\Support\Facades\FilamentView::renderHook('head.start') }}

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Security-Policy" content="base-uri 'none';">

        {{-- Fix para NativePHP/Electron --}}
        <script src="{{ asset('js/nativephp-fix.js') }}"></script>

        @if ($favicon = \Filament\Facades\Filament::getFavicon())
            <link rel="icon" href="{{ $favicon }}">
        @endif

        <title>{{ $title ?? \Filament\Facades\Filament::getDefaultTitle() }}</title>

        @if ($meta = \Filament\Facades\Filament::getMeta())
            @foreach ($meta as $tag)
                @if ($tag instanceof \Illuminate\Contracts\Support\Htmlable)
                    {{ $tag }}
                @else
                    <meta @foreach ($tag as $key => $value) {{ $key }}="{{ $value }}" @endforeach>
                @endif
            @endforeach
        @endif

        @if ($fonts = \Filament\Facades\Filament::getFonts())
            @foreach ($fonts as $font)
                @if ($font instanceof \Illuminate\Contracts\Support\Htmlable)
                    {{ $font }}
                @else
                    <link rel="preconnect" href="{{ $font['url'] }}">
                    <link rel="stylesheet" href="{{ $font['url'] }}">
                @endif
            @endforeach
        @endif

        @foreach (\Filament\Facades\Filament::getStyles() as $name => $path)
            @if ($path instanceof \Illuminate\Contracts\Support\Htmlable)
                {{ $path }}
            @else
                <link rel="stylesheet" href="{{ $path }}" @if ($name) id="{{ $name }}" @endif>
            @endif
        @endforeach

        @foreach (\Filament\Facades\Filament::getScripts() as $name => $path)
            @if ($path instanceof \Illuminate\Contracts\Support\Htmlable)
                {{ $path }}
            @else
                <script defer src="{{ $path }}" @if ($name) id="{{ $name }}" @endif></script>
            @endif
        @endforeach

        {{ \Filament\Support\Facades\FilamentView::renderHook('head.end') }}
    </head>

    <body @class([
        'fi-body min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-950 dark:text-white',
        'dark' => filament()->hasDarkModeForced(),
    ])>
        {{ \Filament\Support\Facades\FilamentView::renderHook('body.start') }}

        {{ $slot }}

        @livewire('notifications')

        {{ \Filament\Support\Facades\FilamentView::renderHook('body.end') }}
    </body>
</html>
