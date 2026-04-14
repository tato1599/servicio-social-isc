<div>
    <x-slot name="header">
        Administración de Materias
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Page Heading -->
        <div class="flex flex-wrap justify-between gap-4">
            <div class="flex min-w-72 flex-col gap-1">
                <h1 class="text-gray-900 text-3xl font-black leading-tight tracking-tight">Administración de Materias</h1>
                <p class="text-gray-500 text-base font-normal leading-normal">Gestiona planes de estudio, materias y los cursos ofrecidos.</p>
            </div>
        </div>

        <!-- Search and Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="w-full sm:max-w-md relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400">search</span>
                </div>
                <input type="text" wire:model.live="search" class="form-input block w-full pl-10 rounded-lg border-gray-200 h-12" placeholder="Buscar materias...">
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('subjects.create') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 rounded-lg h-12 px-6 bg-primary text-white text-sm font-bold shadow-md hover:bg-primary/90 transition-all">
                    <span class="material-symbols-outlined">add</span>
                    Nueva Materia
                </a>
            </div>
        </div>

        <!-- Three-column layout -->
        <div class="grid grid-cols-12 gap-6">
            <!-- Left Column: Departments (Study Plans) -->
            <div class="col-span-12 lg:col-span-3">
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Departamentos</h2>
                    <div class="flex flex-col gap-1">
                        @foreach($departments as $dept)
                            <button 
                                wire:click="selectDepartment({{ $dept->id }})"
                                class="flex justify-between items-center p-3 rounded-lg transition-all {{ $selectedDepartmentId == $dept->id ? 'bg-primary/10 text-primary' : 'hover:bg-gray-100 text-gray-700' }}"
                            >
                                <span class="{{ $selectedDepartmentId == $dept->id ? 'font-bold' : 'font-medium' }} text-sm">{{ $dept->name }}</span>
                                <span class="material-symbols-outlined text-lg">chevron_right</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Middle Column: Subjects Table -->
            <div class="col-span-12 lg:col-span-5">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">Materias del Depto.</h2>
                        <p class="text-sm text-gray-500">{{ $departments->find($selectedDepartmentId)->name ?? '' }}</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Clave</th>
                                    <th class="px-6 py-3">Nombre</th>
                                    <th class="px-6 py-3 text-center">Sem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($subjects as $subject)
                                    <tr 
                                        wire:click="selectSubject({{ $subject->id }})"
                                        class="cursor-pointer transition-all {{ $selectedSubjectId == $subject->id ? 'bg-primary/5' : 'hover:bg-gray-50' }}"
                                    >
                                        <td class="px-6 py-4 font-bold text-primary">{{ $subject->code }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $subject->name }}</td>
                                        <td class="px-6 py-4 text-center">{{ $subject->semester }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">No hay materias en este departamento.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details Pane -->
            <div class="col-span-12 lg:col-span-4">
                @if($selectedSubject)
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm sticky top-24">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 leading-tight">{{ $selectedSubject->code }}</h2>
                                <p class="text-sm text-gray-500">{{ $selectedSubject->name }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('subjects.edit', $selectedSubject) }}" class="flex items-center justify-center size-9 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary transition-colors">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <button 
                                    wire:click="deleteSubject({{ $selectedSubject->id }})" 
                                    wire:confirm="¿Deseas eliminar esta materia?"
                                    class="flex items-center justify-center size-9 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors"
                                >
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Información General</h3>
                                <div class="grid grid-cols-2 gap-y-4 gap-x-2">
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Departamento</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $selectedSubject->department->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Semestre</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $selectedSubject->semester }}° Semestre</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Horas Semanales</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $selectedSubject->weekly_hours }} horas</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Estado</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">ACTIVO</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-100">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Cursos Actuales</h3>
                                <div class="space-y-2">
                                    @php $courses = \App\Models\Course::where('subject_id', $selectedSubject->id)->with('teacher')->get(); @endphp
                                    @forelse($courses as $course)
                                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 flex justify-between items-center group hover:border-primary/30 transition-all cursor-default">
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">Grupo {{ $course->group_code }}</p>
                                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-xs">person</span>
                                                    {{ $course->teacher->name ?? 'Sin asignar' }}
                                                </p>
                                            </div>
                                            <span class="material-symbols-outlined text-gray-300 group-hover:text-primary transition-colors">arrow_forward</span>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-xs text-gray-400 italic">No hay grupos asignados este periodo.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-10 flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">info</span>
                        <p class="text-gray-500 font-medium">Selecciona una materia para ver sus detalles</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
