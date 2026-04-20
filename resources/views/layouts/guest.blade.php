<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=lexend:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans text-[#1b1b18] antialiased min-h-screen flex items-center justify-center p-6 lg:p-8 bg-cover bg-center relative overflow-hidden">
        <!-- Persistent Background Layer -->
        <div class="fixed inset-0 z-[-1] bg-cover bg-center blur-sm scale-110 transition-all duration-1000"
             style="background-image: url('{{ asset("itcj.webp") }}');">
            <div class="absolute inset-0 bg-black/20"></div> {{-- Subtle overlay for readability --}}
        </div>

        <div class="w-full flex flex-col items-center justify-center">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
