<div>
    <x-slot name="header">
        Carga Académica y Plan de Estudios
    </x-slot>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6 bg-gradient-to-r from-gray-50/50 to-white">
            <div class="flex items-center gap-4">
                <div class="bg-primary/10 text-primary p-3 rounded-2xl shadow-inner shadow-primary/5">
                    <span class="material-symbols-outlined text-3xl">analytics</span>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-900 leading-tight">Carga Académica Detallada</h2>
                    <p class="text-gray-500 font-medium whitespace-nowrap">Gestión de requerimientos y grupos por materia.</p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-4 w-full xl:w-auto">
                <div class="relative flex-1 min-w-[300px]">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input 
                        type="text" 
                        placeholder="Buscar por materia o código..." 
                        class="w-full pl-12 pr-4 py-3 rounded-2xl border-gray-100 bg-gray-50/50 focus:ring-primary/20 focus:border-primary transition-all text-sm font-bold"
                    >
                </div>
                <div class="flex items-center gap-3 bg-white p-1.5 px-4 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-r border-gray-100 pr-3 mr-1">Periodo</p>
                    <span class="text-primary text-sm font-black">{{ $period }}</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <th class="px-6 py-4">Plan de Estudios</th>
                        <th class="px-6 py-4">Semestre</th>
                        <th class="px-2 py-4">Materia</th>
                        <th class="px-6 py-4">Clave</th>
                        <th class="px-4 py-4 text-center">Alumnos</th>
                        <th class="px-4 py-4 text-center">Ajuste</th>
                        <th class="px-4 py-4 text-center">Grupos</th>
                        <th class="px-2 py-4 text-center bg-blue-50/30">H1</th>
                        <th class="px-2 py-4 text-center bg-blue-50/30">H2</th>
                        <th class="px-2 py-4 text-center bg-blue-50/30">H3</th>
                        <th class="px-2 py-4 text-center bg-blue-50/30">H4</th>
                        <th class="px-2 py-4 text-center bg-blue-50/30 text-primary">H5</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($courses as $course)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-xs font-bold text-gray-500">{{ $course->study_plan ?? 'ISC 2024' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="bg-gray-100 text-gray-700 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase shadow-sm">
                                        {{ $course->subject->semester }}° SEM
                                    </span>
                                </div>
                            </td>
                            <td class="px-2 py-4">
                                <div class="flex flex-col">
                                    <p class="text-sm font-black text-gray-900 leading-tight group-hover:text-primary transition-colors cursor-default">{{ $course->subject->name }}</p>
                                    <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold mt-1">
                                        <span class="material-symbols-outlined text-[14px]">person_check</span>
                                        {{ $course->teacher->name ?? 'MAESTRO POR ASIGNAR' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono font-bold text-primary">{{ $course->subject->code }}</td>
                            <td class="px-4 py-4 text-center text-sm font-bold text-gray-700">{{ $course->students_count }}</td>
                            <td class="px-4 py-4 text-center text-sm font-bold text-gray-400 italic">{{ $course->students_count_adjusted }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="bg-blue-100 text-blue-700 text-xs font-black px-3 py-1 rounded-lg">
                                    {{ $course->groups_count }}
                                </span>
                            </td>
                            <td class="px-2 py-4 text-center font-black text-xs text-blue-600">{{ $course->h1 }}</td>
                            <td class="px-2 py-4 text-center font-black text-xs text-blue-600">{{ $course->h2 }}</td>
                            <td class="px-2 py-4 text-center font-black text-xs text-blue-600">{{ $course->h3 }}</td>
                            <td class="px-2 py-4 text-center font-black text-xs text-blue-600">{{ $course->h4 }}</td>
                            <td class="px-2 py-4 text-center font-black text-xs text-primary">{{ $course->h5 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center opacity-30">
                                    <span class="material-symbols-outlined text-6xl mb-2">table_rows</span>
                                    <p class="text-lg font-black uppercase tracking-widest">No hay datos de carga académica</p>
                                    <p class="text-sm">Importa requerimientos para poblar esta tabla.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
