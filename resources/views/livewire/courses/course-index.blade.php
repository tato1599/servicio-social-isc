<div>
    <x-slot name="header">
        Gestión de Horarios y Cursos
    </x-slot>

    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="relative w-full sm:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400 text-xl">search</span>
                </div>
                <input type="text" wire:model.live="search" class="form-input block w-full pl-10 rounded-lg border-gray-200" placeholder="Buscar por materia o profesor...">
            </div>
            
            <a href="{{ route('courses.create') }}" class="flex items-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-medium hover:bg-primary/90 w-full sm:w-auto justify-center">
                <span class="material-symbols-outlined">calendar_add_on</span>
                Asignar Horario
            </a>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-4">Materia</th>
                            <th class="px-6 py-4">Maestro</th>
                            <th class="px-6 py-4">Grupo / Periodo</th>
                            <th class="px-6 py-4">Horarios Asignados</th>
                            <th class="px-6 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($courses as $course)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ $course->subject->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $course->subject->code }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-800">{{ $course->teacher->name ?? 'Maestro por asignar' }}</p>
                                    @if($course->teacher)
                                        <p class="text-xs {{ $course->teacher->type === 'base' ? 'text-blue-500' : 'text-purple-500' }}">{{ ucfirst($course->teacher->type) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded bg-gray-100 text-xs">Gp: {{ $course->group_code }}</span>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ $course->period }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($course->schedules as $sched)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-primary/10 text-primary text-[10px]">
                                                @php
                                                    $days = [1=>'Lun', 2=>'Mar', 3=>'Mie', 4=>'Jue', 5=>'Vie', 6=>'Sab'];
                                                @endphp
                                                {{ $days[$sched->day_of_week] }} {{ substr($sched->start_time, 0, 5) }}
                                            </span>
                                        @empty
                                            <span class="text-red-400 text-xs italic">Sin horario</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('courses.edit', $course) }}" class="text-primary hover:text-primary/80">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <button wire:click="deleteCourse({{ $course->id }})" wire:confirm="¿Está seguro?" class="text-red-500 hover:text-red-700">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    No se han realizado asignaciones de horario.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($courses->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
