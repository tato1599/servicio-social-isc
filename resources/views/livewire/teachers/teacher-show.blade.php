<div>
    <x-slot name="header">
        Perfil del Maestro
    </x-slot>

    <!-- Breadcrumb & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-2">
            <a class="flex items-center text-primary hover:underline font-medium text-sm group" href="{{ route('teachers.index') }}">
                <span class="material-symbols-outlined text-sm mr-1 group-hover:-translate-x-1 transition-transform">arrow_back</span>
                Regresar a Docentes
            </a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-500 text-sm">Perfil del Maestro</span>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('teachers.edit', $teacher) }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 text-sm font-semibold hover:bg-gray-50 transition-all shadow-sm">
                <span class="material-symbols-outlined text-lg">edit</span>
                Editar Perfil
            </a>
            <button class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
                <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                Descargar Horario
            </button>
        </div>
    </div>

    <!-- Teacher Info Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row gap-8 items-start md:items-center">
            <div class="relative group">
                <div class="size-32 rounded-2xl border-4 border-white overflow-hidden shadow-lg bg-gray-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-5xl text-gray-300">person</span>
                </div>
                <div class="absolute -bottom-2 -right-2 bg-green-500 size-6 rounded-full border-2 border-white shadow-sm" title="Activo"></div>
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $teacher->name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wide">{{ $teacher->type }}</span>
                </div>
                <p class="text-gray-600 text-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-xl">engineering</span>
                    {{ $teacher->department->name ?? 'Sin Departamento' }} | Especialista
                </p>
                <div class="flex flex-wrap gap-4 mt-4">
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <span class="material-symbols-outlined text-lg">mail</span>
                        {{ strtolower(str_replace(' ', '.', $teacher->name)) }}@universidad.edu
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <span class="material-symbols-outlined text-lg">call</span>
                        +52 55 #### ####
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <span class="material-symbols-outlined text-lg">badge</span>
                        ID: {{ $teacher->employee_id }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8 pt-6 border-t border-gray-100">
            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Horas Semanales</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_hours'] }} <span class="text-sm font-normal text-gray-400">/ {{ $teacher->max_hours }} hrs (Min: {{ $teacher->min_hours }})</span></p>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Materias</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_subjects'] }} <span class="text-sm font-normal text-gray-400">activas</span></p>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Alumnos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] }}</p>
            </div>
            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Prioridad</p>
                <div class="flex items-center gap-2">
                    <p class="text-2xl font-bold text-gray-900">{{ $teacher->priority }}</p>
                    <span class="flex items-center text-blue-500 text-xs font-bold">
                        <span class="material-symbols-outlined text-xs">trending_up</span>
                        Nivel {{ $teacher->priority >= 8 ? 'Alto' : 'Medio' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Section -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Horario Semanal de Clases</h2>
                <p class="text-sm text-gray-500 mt-1">Ciclo Escolar 2026 | Periodo Actual</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex rounded-lg border border-gray-200 p-1 bg-gray-50">
                    <button class="px-3 py-1 bg-white shadow-sm rounded-md text-xs font-bold text-primary">LISTA</button>
                    <button class="px-3 py-1 text-xs font-bold text-gray-500 hover:text-primary transition-colors">VISTA SEMANA</button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold tracking-widest">
                        <th class="px-6 py-4 border-b border-gray-100">Materia</th>
                        <th class="px-6 py-4 border-b border-gray-100">Código</th>
                        <th class="px-6 py-4 border-b border-gray-100">Grupo</th>
                        <th class="px-6 py-4 border-b border-gray-100">Horario (Días)</th>
                        <th class="px-6 py-4 border-b border-gray-100">Aula</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($teacher->courses as $course)
                        <tr class="hover:bg-primary/[0.02] transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                        {{ substr($course->subject->name, 0, 2) }}
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $course->subject->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-sm font-mono text-gray-500">{{ $course->subject->code }}</td>
                            <td class="px-6 py-5 text-sm font-medium text-gray-700">G-{{ $course->group_code }}</td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-1">
                                    @php $schedules = \App\Models\Schedule::where('course_id', $course->id)->get(); @endphp
                                    @foreach($schedules->groupBy(fn($s) => $s->start_time . '-' . $s->end_time) as $time => $slots)
                                        <div class="text-sm text-gray-600">
                                            <span class="font-bold text-gray-400">{{ substr($time, 0, 5) }} - {{ substr($time, 9, 5) }}</span>
                                            <span class="ml-2">
                                                @foreach($slots as $slot)
                                                    @php $days = ['','L','M','M','J','V','S']; @endphp
                                                    <span class="size-5 inline-flex items-center justify-center rounded-full bg-primary text-white text-[9px] font-bold">{{ $days[$slot->day_of_week] }}</span>
                                                @endforeach
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                @if($schedules->first())
                                    <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-bold">{{ $schedules->first()->classroom->name ?? 'Sin salón' }}</span>
                                @else
                                    <span class="text-gray-400 text-xs italic">Sin aula</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">No hay materias asignadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
