<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Repairo') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-white via-[#f6f6ff] to-[#1800ad]/10">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-0">
        <!-- Logo -->
        <div class="mb-6 sm:mb-8 -mt-4 sm:-mt-6">
            <a href="/" class="flex items-center justify-center">
                <img src="{{ asset('images/repairo-logo.png') }}" 
                     alt="Repairo Logo" 
                     class="w-36 h-36 sm:w-44 sm:h-44 object-contain drop-shadow-lg transition-transform duration-300 hover:scale-105">
            </a>
        </div>

        <!-- Card -->
        <div class="w-full sm:max-w-md bg-white shadow-lg rounded-2xl border border-gray-100 p-6 sm:p-8 backdrop-blur-sm">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <p class="mt-8 text-sm text-gray-400 text-center">
            © {{ date('Y') }} <span class="font-medium text-[#1800ad]">Repairo</span>. All rights reserved.
        </p>
    </div>
</body>
</html>
