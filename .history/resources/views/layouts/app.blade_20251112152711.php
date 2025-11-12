<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <title>{{ config('app.name', 'Repairo: Smartphone and Gadget Fix') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased bg-gray-50">
        {{-- Wrapper --}}
        <div class="min-h-screen flex flex-col">

            {{-- Navigation Bar --}}
            @include('layouts.navigation')

            {{-- Optional Header --}}
            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Page Content --}}
            <main class="flex-1">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    {{-- Flash messages --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
                    @endif
                </div>

                {{-- Page Section --}}
                @yield('content')
            </main>
        </div>
    </body>
</html>
