<x-dashboard-layout>
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-[#1A2836] border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-gray-700 dark:text-gray-300 text-base font-medium leading-normal">Total de Maestros</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">{{ $stats['total_teachers'] }}</p>
            <p class="text-green-600 dark:text-green-400 text-sm font-medium leading-normal">+0 esta semana</p>
        </div>
        <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-[#1A2836] border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-gray-700 dark:text-gray-300 text-base font-medium leading-normal">Salones Ocupados</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">{{ $stats['occupied_classrooms'] }}</p>
            <p class="text-green-600 dark:text-green-400 text-sm font-medium leading-normal">Activos ahora</p>
        </div>
        <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-[#1A2836] border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-gray-700 dark:text-gray-300 text-base font-medium leading-normal">Conflictos de Horario</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">{{ $stats['schedule_conflicts'] }}</p>
            <p class="text-yellow-600 dark:text-yellow-400 text-sm font-medium leading-normal">Por resolver</p>
        </div>
        <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-[#1A2836] border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-gray-700 dark:text-gray-300 text-base font-medium leading-normal">Servicio Social Activo</p>
            <p class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">{{ $stats['active_social_service'] }}</p>
            <p class="text-blue-600 dark:text-blue-400 text-sm font-medium leading-normal">Estudiantes</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-6">
        <div class="xl:col-span-2 flex flex-col gap-6">
            <!-- Conflict Section -->
            <div class="bg-white dark:bg-[#1A2836] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <h2 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] px-6 pt-5 pb-3">Conflictos de Horario por Resolver</h2>
                <div class="flex flex-col divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="flex items-center gap-4 px-6 min-h-[72px] py-3 justify-between">
                        <div class="flex items-center gap-4">
                            <div class="text-red-500 flex items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/40 shrink-0 size-12">
                                <span class="material-symbols-outlined text-2xl">error</span>
                            </div>
                            <div class="flex flex-col justify-center">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal line-clamp-1">Doble asignación: Dr. Pérez</p>
                                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal line-clamp-2">Lunes 10:00 AM en Salón A y Salón B</p>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 text-sm font-medium leading-normal w-fit">
                                <span class="truncate">Resolver</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Classes Table -->
            <div class="bg-white dark:bg-[#1A2836] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <h2 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] px-6 pt-5 pb-3">Próximas Clases</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3" scope="col">Hora</th>
                                <th class="px-6 py-3" scope="col">Materia</th>
                                <th class="px-6 py-3" scope="col">Maestro</th>
                                <th class="px-6 py-3" scope="col">Salón</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">10:00 - 11:00</td>
                                <td class="px-6 py-4">Cálculo Diferencial</td>
                                <td class="px-6 py-4">Dr. Juan Pérez</td>
                                <td class="px-6 py-4">Salón A</td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">11:00 - 12:00</td>
                                <td class="px-6 py-4">Bases de Datos</td>
                                <td class="px-6 py-4">Mtra. Ana Gómez</td>
                                <td class="px-6 py-4">Lab. Cómputo 1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <!-- Chart Placeholder -->
            <div class="bg-white dark:bg-[#1A2836] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 flex flex-col gap-4 h-fit">
                <h2 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Uso de Recursos</h2>
                <div class="w-full h-48 flex items-center justify-center">
                    <img class="max-w-full max-h-full" src="https://lh3.googleusercontent.com/aida-public/AB6AXuATZWteh9LyH2SNA4YODOdju_6avnoUSiFj-kG2r6DumSrsdJKT7V4kEQ_Q3D4zu3mWuQsv5Yf_y4aCkH9Yqx1E5RfdVnu6hJMU-TvwqkSz-eZbTV3Tm9ebmBLoXL_65D10iKD7BzlpMKT1Vb176hlVwF6hTUwgEH7l-TInQ9vR3onLvVltnu9qGYnK84sdM2YuGJjz2PXgkbcIAqXCoklEZcT8m4OF4KX8s6d0vw40_iBUdPPqYM0AHnQIYCIFsVhUBsSnJ5sdq0k"/>
                </div>
                <div class="flex justify-center gap-6 text-sm">
                    <div class="flex items-center gap-2"><span class="size-3 rounded-full bg-primary"></span><span class="text-gray-600 dark:text-gray-400">Asignados</span></div>
                    <div class="flex items-center gap-2"><span class="size-3 rounded-full bg-gray-200 dark:bg-gray-600"></span><span class="text-gray-600 dark:text-gray-400">Disponibles</span></div>
                </div>
            </div>

            <!-- Social Service Widget -->
            <div class="bg-white dark:bg-[#1A2836] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 flex flex-col gap-4">
                <h2 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Resumen de Servicio Social</h2>
                <div class="flex flex-col gap-3">
                    <div class="flex justify-between items-baseline">
                        <p class="text-gray-600 dark:text-gray-400">Estudiantes Activos</p>
                        <p class="font-bold text-gray-900 dark:text-white">25</p>
                    </div>
                </div>
                <button class="w-full mt-2 flex items-center justify-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-medium hover:bg-primary/90">
                    <span>Gestionar Servicio Social</span>
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </button>
            </div>
        </div>
    </div>
</x-dashboard-layout>
