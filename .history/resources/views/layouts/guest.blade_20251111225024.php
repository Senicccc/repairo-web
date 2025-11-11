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
    <div class="min-h-screen flex flex-col items-center justify-center px-6 sm:px-0 py-10">

        <!-- Card -->
        <div class="w-full max-w-md bg-white rounded-lg border border-gray-100 p-8 sm:p-10 backdrop-blur-sm animate-fade-in">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <p class="mt-10 text-sm text-gray-400 text-center">
            © {{ date('Y') }} Repairo. All rights reserved.
        </p>
    </div>

    <!-- Smooth Animations -->
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.6s ease-out both;
        }
    </style>
</body>
</html>
