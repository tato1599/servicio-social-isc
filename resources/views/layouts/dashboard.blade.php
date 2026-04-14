<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <div class="flex h-screen overflow-hidden">
        <!-- SideNavBar -->
        <aside class="w-64 flex-shrink-0 bg-white dark:bg-[#1A2836] p-4 flex flex-col justify-between border-r border-gray-200 dark:border-gray-700">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3 px-2">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCkYe-g4r1qHPzdQc_wWYO1b0BoKuYyu10jPDemyp1KAVUtU-XnIjTPfo-uIJoekS8cYO9gDbnjo4tyfTxpxBzaTlJbSnB5FHsw1bx-4nPH4Dt2hlHagoUFf_y27hnuya6_KrJJUi6GiVFtV8K_c5RcNDqjkUSo1DuNhG5BCFvfXWpo1LkOYPwta5JmcfFr2ezsXUex3upNRJ-RZhh1QJ-1cCprorevcL3vFsX35o4Me3OqStZ9vA3AnZgaoL9PpiRoF7FWdl4kGIs');"></div>
                    <div class="flex flex-col">
                        <h1 class="text-gray-900 dark:text-white text-base font-medium leading-normal">Sistema de Gestión</h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Panel Administrativo</p>
                    </div>
                </div>
                <nav class="flex flex-col gap-2 mt-4">
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary/20 text-primary dark:bg-primary/30' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300' }}" href="{{ route('dashboard') }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <p class="text-sm font-medium leading-normal">Dashboard</p>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('courses.*') ? 'bg-primary/20 text-primary dark:bg-primary/30' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300' }}" href="{{ route('courses.index') }}">
                        <span class="material-symbols-outlined">calendar_month</span>
                        <p class="text-sm font-medium leading-normal">Horarios</p>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('teachers.*') ? 'bg-primary/20 text-primary dark:bg-primary/30' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300' }}" href="{{ route('teachers.index') }}">
                        <span class="material-symbols-outlined">person</span>
                        <p class="text-sm font-medium leading-normal">Maestros</p>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('subjects.*') ? 'bg-primary/20 text-primary dark:bg-primary/30' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300' }}" href="{{ route('subjects.index') }}">
                        <span class="material-symbols-outlined">book</span>
                        <p class="text-sm font-medium leading-normal">Materias</p>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300" href="#">
                        <span class="material-symbols-outlined">work</span>
                        <p class="text-sm font-medium leading-normal">Servicio Social</p>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300" href="#">
                        <span class="material-symbols-outlined">bar_chart</span>
                        <p class="text-sm font-medium leading-normal">Reportes</p>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300" href="#">
                        <span class="material-symbols-outlined">settings</span>
                        <p class="text-sm font-medium leading-normal">Configuración</p>
                    </a>
                </nav>
            </div>
            
            <div class="px-2 pb-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400">
                        <span class="material-symbols-outlined">logout</span>
                        <p class="text-sm font-medium leading-normal">Cerrar Sesión</p>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            <!-- TopNavBar -->
            <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-gray-700 px-10 py-3 bg-white dark:bg-[#1A2836] sticky top-0 z-10">
                <div class="flex items-center gap-4 text-gray-900 dark:text-white">
                    <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">{{ $header ?? 'Resumen General' }}</h2>
                </div>
                <div class="flex flex-1 justify-end items-center gap-4">
                    <label class="relative flex-col min-w-40 !h-10 max-w-64 hidden sm:flex">
                        <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                            <div class="text-gray-500 dark:text-gray-400 flex bg-gray-100 dark:bg-gray-800 items-center justify-center pl-3 pr-2 rounded-l-lg">
                                <span class="material-symbols-outlined text-xl">search</span>
                            </div>
                            <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-gray-100 dark:bg-gray-800 h-full placeholder:text-gray-500 dark:placeholder:text-gray-400 px-2 rounded-l-none text-base font-normal leading-normal" placeholder="Search..."/>
                        </div>
                    </label>
                    <button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 w-10 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style="background-image: url('{{ auth()->user()->profile_photo_url }}');"></div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6 lg:p-10">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('modals')
    @livewireScripts
</body>
</html>
