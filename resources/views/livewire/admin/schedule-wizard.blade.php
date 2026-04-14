<div>
    <x-slot name="header">
        Asistente de Horarios (Wizard) - {{ $period }}
    </x-slot>

    <div class="max-w-[1400px] mx-auto space-y-8 pb-20 mt-8">

        <!-- Breadcrumbs / Steps -->
        <div class="flex items-center justify-center space-x-2 mb-8 flex-wrap">
            <div class="flex items-center space-x-1">
                <div
                    class="w-8 h-8 {{ $currentStep >= 1 ? 'bg-primary text-white shadow-lg' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300">
                    1</div>
                <span
                    class="{{ $currentStep >= 1 ? 'text-primary font-black' : 'text-gray-400 font-medium' }} text-xs">Importación</span>
            </div>
            <div class="w-10 h-0.5 {{ $currentStep >= 2 ? 'bg-primary' : 'bg-gray-200' }}"></div>

            <div class="flex items-center space-x-1">
                <div
                    class="w-8 h-8 {{ $currentStep >= 2 ? 'bg-primary text-white shadow-lg' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300">
                    2</div>
                <span
                    class="{{ $currentStep >= 2 ? 'text-primary font-black' : 'text-gray-400 font-medium' }} text-xs">Confirmar
                    Horarios</span>
            </div>
            <div class="w-10 h-0.5 {{ $currentStep >= 3 ? 'bg-primary' : 'bg-gray-200' }}"></div>

            <div class="flex items-center space-x-1">
                <div
                    class="w-8 h-8 {{ $currentStep >= 3 ? 'bg-primary text-white shadow-lg' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300">
                    3</div>
                <span
                    class="{{ $currentStep >= 3 ? 'text-primary font-black' : 'text-gray-400 font-medium' }} text-xs">Asignar
                    Aulas</span>
            </div>
            <div class="w-10 h-0.5 {{ $currentStep >= 4 ? 'bg-primary' : 'bg-gray-200' }}"></div>

            <div class="flex items-center space-x-1">
                <div
                    class="w-8 h-8 {{ $currentStep >= 4 ? 'bg-primary text-white shadow-lg' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300">
                    4</div>
                <span
                    class="{{ $currentStep >= 4 ? 'text-primary font-black' : 'text-gray-400 font-medium' }} text-xs">Asignar
                    Maestros</span>
            </div>
        </div>

        <!-- Breadcrumbs / Steps -->
        @if ($currentStep === 1)
            <div
                class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-500 max-w-4xl mx-auto">
                <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="bg-primary/10 text-primary p-3 rounded-xl">
                                <span class="material-symbols-outlined text-3xl">upload_file</span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 leading-tight">Paso 1: Detectar Grupos a
                                    Programar</h2>
                                <p class="text-gray-500 font-medium">Sube el listado en formato CSV base que contenga la
                                    planeación requerida.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-bold text-gray-700">Seleccionar Archivo .CSV</label>
                        <input type="file" wire:model="file" accept=".csv"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:transition-all cursor-pointer bg-gray-50 rounded-xl border border-gray-200 focus:border-primary focus:ring-0">

                        <div wire:loading wire:target="file"
                            class="text-primary mt-3 text-sm font-bold animate-pulse flex items-center gap-2">
                            <span class="material-symbols-outlined animate-spin text-sm">refresh</span>
                            Procesando y creando borradores...
                        </div>
                    </div>

                    @if (!empty($importedCourses))
                        <div class="mt-8 border rounded-xl overflow-hidden shadow-sm">
                            <table class="w-full text-left text-sm">
                                <thead
                                    class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest border-b">
                                    <tr>
                                        <th class="px-6 py-3">Materia / Clave</th>
                                        <th class="px-6 py-3 text-center">Alumnos</th>
                                        <th class="px-6 py-3 text-center">Opciones Horario</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($importedCourses as $row)
                                        <tr class="hover:bg-primary/5 transition-colors">
                                            <td class="px-6 py-3">
                                                <div class="font-bold text-gray-700">{{ $row['subject_name'] }}
                                                    ({{ $row['group'] }})</div>
                                                <div class="text-[10px] font-mono text-primary font-bold">
                                                    {{ $row['subject_code'] }}</div>
                                            </td>
                                            <td class="px-6 py-3 text-center font-bold text-gray-600">
                                                {{ $row['students'] }}
                                            </td>
                                            <td class="px-6 py-3 text-center">
                                                <span
                                                    class="font-mono bg-blue-50 text-blue-600 px-3 py-1 rounded font-black text-xs">
                                                    {{ $row['slots'] ?: 'SIN DEFINIR' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button wire:click="nextStep"
                                class="flex items-center gap-2 px-8 py-3 bg-primary text-white rounded-xl font-black shadow-lg shadow-primary/30 hover:scale-105 active:scale-95 transition-all">
                                <span>Paso 2: Confirmar Horarios</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- PASO 2 -->
        @if ($currentStep === 2)
            <div
                class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden animate-in fade-in duration-500 max-w-5xl mx-auto">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-black text-gray-900 leading-tight">Paso 2: Confirmar Horarios Base</h2>
                        <p class="text-gray-500 font-medium text-sm">Selecciona el bloque de horario definitivo para
                            aquellos cursos que tengan múltiples opciones.</p>
                    </div>
                </div>

                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest rounded-xl">
                            <tr>
                                <th class="px-6 py-3 rounded-l-xl">Cursos Importados</th>
                                <th class="px-6 py-3 text-center">Opciones (JSON)</th>
                                <th class="px-6 py-3 rounded-r-xl">Selección de Horario Final</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($coursesStep2 as $course)
                                @php
                                    $slotsArr = $course->possible_slots
                                        ? json_decode($course->possible_slots, true)
                                        : [];
                                @endphp
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $course->subject->name }}
                                            ({{ $course->group_code }})</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex gap-1 justify-center flex-wrap">
                                            @forelse($slotsArr as $slot)
                                                <span
                                                    class="font-mono bg-indigo-50 border border-indigo-100 text-indigo-700 px-2 py-0.5 rounded font-black text-xs">
                                                    {{ $slot }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400">Libre / N/A</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(count($slotsArr) > 1)
                                            <select wire:model="selectedFinalSlots.{{ $course->id }}"
                                                class="block w-full text-sm rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-gray-50 font-bold text-gray-700">
                                                <option value="">Selecciona horario</option>
                                                @foreach ($slotsArr as $s)
                                                    @if(isset(\App\Helpers\TimeSlotHelper::SLOTS[$s]))
                                                        <option value="{{ $s }}">Bloque {{ $s }} 
                                                            ({{ \App\Helpers\TimeSlotHelper::SLOTS[$s]['start'] }} - {{ \App\Helpers\TimeSlotHelper::SLOTS[$s]['end'] }})
                                                        </option>
                                                    @else
                                                        <option value="{{ $s }}">Bloque {{ $s }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @elseif(count($slotsArr) === 1)
                                            <div class="px-3 py-2 bg-green-50 border border-green-100 text-green-700 rounded-xl text-xs font-bold flex items-center justify-center gap-2">
                                                <span class="material-symbols-outlined text-sm">lock</span>
                                                Fijo en Bloque {{ $slotsArr[0] }}
                                            </div>
                                        @else
                                            <select wire:model="selectedFinalSlots.{{ $course->id }}"
                                                class="block w-full text-sm rounded-xl border-red-300 bg-red-50 shadow-sm focus:border-red-500 font-bold text-red-700">
                                                <option value="">Falta opción (Asignar libre)</option>
                                                @foreach (\App\Helpers\TimeSlotHelper::SLOTS as $s => $times)
                                                    <option value="{{ $s }}">Bloque {{ $s }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-100 flex justify-between bg-gray-50/50">
                    <button wire:click="previousStep"
                        class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-colors">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Regresar
                    </button>
                    <button wire:click="nextStep"
                        class="flex items-center gap-2 px-8 py-3 bg-primary text-white rounded-xl font-black shadow-lg shadow-primary/30 hover:scale-105 active:scale-95 transition-all">
                        <span>Paso 3: Asignar Aulas (Avanzar)</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- PASO 3: DRAG & DROP AULAS -->
        @if ($currentStep === 3)
            <div class="animate-in fade-in duration-500 flex flex-col lg:flex-row gap-6 w-full" x-data="{ isDraggingCourse: false }">

                <!-- Sidebar (Cursos Sin Aula) -->
                <div
                    class="w-full lg:w-1/4 bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden flex flex-col h-[750px]">
                    <div class="p-5 border-b border-gray-100 bg-gray-900 text-white shrink-0">
                        <h2 class="text-lg font-black leading-tight flex items-center gap-2">
                            <span class="material-symbols-outlined">meeting_room</span>
                            1. Cursos sin Aula
                        </h2>
                        <p class="text-gray-400 font-medium text-xs mt-1">Arrastra un curso hacia el <strong
                                class="text-white">encabezado (nombre)</strong> del aula deseada. Sólo así avanzaremos.</p>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar bg-gray-50 min-h-0">
                        @forelse($this->getUnassignedClassroomCourses() as $course)
                            <div draggable="true"
                                @dragstart="isDraggingCourse = true; $event.dataTransfer.setData('type', 'course'); $event.dataTransfer.setData('course_id', '{{ $course->id }}'); $event.dataTransfer.effectAllowed = 'move';"
                                @dragend="isDraggingCourse = false;"
                                class="cursor-grab active:cursor-grabbing bg-white border-2 border-transparent shadow-sm hover:shadow hover:border-primary/40 rounded-2xl p-4 transition-all group relative overflow-hidden">

                                <!-- Highlight bar -->
                                <div
                                    class="absolute left-0 top-0 bottom-0 w-1 bg-primary/80 scale-y-0 group-hover:scale-y-100 transition-transform origin-top">
                                </div>

                                <div class="flex justify-between items-start mb-2 pl-1">
                                    <div
                                        class="font-black text-gray-800 text-xs leading-tight group-hover:text-primary transition-colors">
                                        {{ $course->subject->name ?? 'Materia' }} ({{ $course->group_code }})
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-3 pl-1">
                                    <span class="bg-gray-100 text-gray-600 text-[10px] font-black px-2 py-1 rounded">
                                        HORA: {{ $course->final_slot }}
                                    </span>
                                    <div
                                        class="text-[10px] font-bold bg-blue-50 text-blue-700 px-2 py-1 rounded flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[12px]">group</span>
                                        {{ $course->students_count }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center text-gray-400 text-center">
                                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">task_alt</span>
                                <p class="font-bold text-sm text-gray-500">Todos los cursos tienen aula.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Matriz Principal -->
                <div
                    class="w-full lg:w-3/4 bg-white rounded-3xl border border-gray-200 shadow-xl flex flex-col overflow-hidden h-[750px]">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Matriz Física</h2>
                        </div>
                        <div class="flex gap-3">
                            <button wire:click="previousStep"
                                class="flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-colors">
                                <span class="material-symbols-outlined text-sm">arrow_back</span>
                            </button>
                            <button wire:click="nextStep"
                                class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-xl font-black shadow-lg hover:scale-105 transition-all">
                                <span>Siguiente Paso</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-auto custom-scrollbar bg-white" id="step3-matrix-container">
                        <table class="w-full border-collapse text-left min-w-max">
                            <thead class="sticky top-0 z-20 shadow-sm relative bg-gray-100">
                                <tr>
                                    <th
                                        class="px-4 py-4 border-b border-r border-gray-200 sticky left-0 z-30 bg-gray-100 text-center w-24">
                                        HORA \ AULA
                                    </th>
                                    @foreach ($classrooms as $room)
                                        <!-- DROPZONE PARA CABECERA (AULA) -->
                                        <th class="border-b border-gray-200 min-w-[180px] p-0 relative transition-all duration-300"
                                            x-data="{ isHovered: false }"
                                            :class="{
                                                'border-dashed border-2 border-primary bg-primary/10 shadow-inner scale-105 ring-4 ring-primary/20 text-primary z-40': isHovered,
                                                'border-dashed border-2 border-primary/50 bg-white ring-4 ring-primary/5 text-gray-800 shadow-md': isDraggingCourse && !isHovered,
                                                'bg-gray-100 text-gray-800': !isDraggingCourse && !isHovered
                                            }"
                                            @dragover.prevent="if(isDraggingCourse) { isHovered = true }"
                                            @dragleave.prevent="isHovered = false"
                                            @drop.prevent="
                                            isHovered = false; 
                                            isDraggingCourse = false;
                                            let cId = $event.dataTransfer.getData('course_id');
                                            let type = $event.dataTransfer.getData('type');
                                            if(cId && type === 'course') { 
                                                $wire.assignCourseToClassroom(cId, '{{ $room->id }}'); 
                                            }
                                        ">
                                            <div
                                                class="px-4 py-4 h-full flex flex-col justify-center items-center pointer-events-none">
                                                <span class="font-black text-sm">{{ $room->name }}</span>
                                                <span class="text-[10px] opacity-70 font-bold mt-0.5">Capacidad:
                                                    {{ $room->capacity }}</span>
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach (\App\Helpers\TimeSlotHelper::SLOTS as $slotKey => $slotData)
                                    <tr>
                                        <td
                                            class="sticky left-0 z-10 bg-gray-50 border-r border-gray-200 px-3 py-3 text-center align-top shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">
                                            <div class="font-black text-xl text-primary">{{ $slotKey }}</div>
                                        </td>

                                        @foreach ($classrooms as $room)
                                            @php
                                                $cellCourse = $this->getMatrixStep3()[$slotKey][$room->id] ?? null;
                                            @endphp

                                            <td class="border-r border-gray-100 p-2 align-top h-24 relative bg-white transition-opacity duration-300"
                                                :class="isDraggingCourse ? 'opacity-40' : 'opacity-100'">
                                                @if ($cellCourse)
                                                    <!-- Curso Asignado (Muestra Info Básica) -->
                                                    <div wire:click="removeCourseFromClassroom({{ $cellCourse['id'] }})"
                                                        class="cursor-pointer h-full border border-gray-200 bg-gray-50 rounded-xl p-3 flex flex-col justify-between hover:border-red-300 hover:bg-red-50 transition-all select-none group/card relative" title="Haz click para remover">

                                                        <div
                                                            class="absolute right-2 top-2 opacity-0 group-hover/card:opacity-100 text-red-500 font-bold text-[10px] flex items-center transition-opacity">
                                                            <span
                                                                class="material-symbols-outlined text-[14px]">delete</span>
                                                        </div>

                                                        <div>
                                                            <div
                                                                class="font-black text-xs leading-tight text-gray-800 line-clamp-2 pr-4">
                                                                {{ $cellCourse['subject_name'] }}
                                                            </div>
                                                            <div class="text-[10px] text-gray-500 mt-0.5 font-bold">
                                                                Gpo: {{ $cellCourse['group'] }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- PASO 4: DRAG & DROP MAESTROS (A CELDAS OCUPADAS) -->
        @if ($currentStep === 4)
            <div class="animate-in fade-in duration-500 flex flex-col lg:flex-row gap-6 w-full"
                x-data="{ isDraggingTeacher: false }">

                <!-- Sidebar (Maestros) -->
                <div
                    class="w-full lg:w-1/4 bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden flex flex-col h-[750px] shrink-0">
                    <div class="p-5 border-b border-gray-100 bg-gray-900 text-white shrink-0">
                        <h2 class="text-lg font-black leading-tight flex items-center gap-2">
                            <span class="material-symbols-outlined">person_add</span>
                            2. Catálogo Maestros
                        </h2>
                        <p class="text-gray-400 font-medium text-xs mt-1">Arrastra un maestro directo a la <strong
                                class="text-white">tarjeta del curso en la tabla</strong>.</p>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-2 custom-scrollbar bg-gray-50 min-h-0">
                        @foreach ($this->getTeachersWithLoad() as $teacher)
                            <div draggable="true"
                                @dragstart="isDraggingTeacher = true; $event.dataTransfer.setData('type', 'teacher'); $event.dataTransfer.setData('teacher_id', '{{ $teacher->id }}'); $event.dataTransfer.effectAllowed = 'move';"
                                @dragend="isDraggingTeacher = false;"
                                class="cursor-grab active:cursor-grabbing border shadow-sm hover:shadow hover:border-primary/40 rounded-xl p-3 px-4 transition-all flex items-center justify-between gap-3 {{ $teacher->status_color ?? 'bg-white border-gray-200' }}">

                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div
                                        class="bg-black/5 w-8 h-8 rounded-full flex items-center justify-center shrink-0 opacity-80">
                                        <span class="material-symbols-outlined text-[16px] font-bold">person</span>
                                    </div>
                                    <div class="font-black text-xs leading-tight truncate">
                                        {{ $teacher->name }}
                                    </div>
                                </div>
                                
                                <div class="text-[10px] font-black shrink-0 bg-black/10 px-2 py-1 rounded-md flex flex-col items-center justify-center leading-none" title="Mínimo {{ $teacher->min_hours }}h">
                                    <span>{{ $teacher->current_hours }} / {{ $teacher->max_hours }}h</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Matriz Principal -->
                <div
                    class="w-full lg:w-3/4 bg-white rounded-3xl border border-gray-200 shadow-xl flex flex-col overflow-hidden h-[750px]">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Asignación Docente</h2>
                            <p class="text-xs text-gray-500 font-bold mt-1 tracking-tight">Celdas vacías bloqueadas.
                                Arrastra a los cursos confirmados.</p>
                        </div>
                        <div class="flex gap-3">
                            <button wire:click="previousStep"
                                class="flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-colors">
                                <span class="material-symbols-outlined text-sm">arrow_back</span>
                            </button>
                            <button wire:click="publishSchedules" wire:loading.attr="disabled"
                                class="flex items-center gap-2 px-6 py-2 bg-gray-900 text-white rounded-xl font-black shadow-lg hover:scale-105 active:scale-95 transition-all">
                                <span wire:loading.remove wire:target="publishSchedules"
                                    class="material-symbols-outlined text-sm">publish</span>
                                <span wire:loading wire:target="publishSchedules"
                                    class="material-symbols-outlined text-sm animate-spin">refresh</span>
                                <span>FINALIZAR / PUBLICAR</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-auto custom-scrollbar relative bg-white" id="step4-matrix-container">
                        <table class="w-full border-collapse text-left min-w-max">
                            <thead class="sticky top-0 z-20 shadow-sm">
                                <tr>
                                    <th
                                        class="px-4 py-4 border-b border-r border-gray-200 sticky left-0 z-30 bg-gray-100 text-center w-24">
                                        HORA \ AULA
                                    </th>
                                    @foreach ($classrooms as $room)
                                        <th
                                            class="bg-gray-50 border-b border-gray-200 min-w-[200px] px-4 py-3 text-center">
                                            <div class="font-black text-sm text-gray-800">{{ $room->name }}</div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach (\App\Helpers\TimeSlotHelper::SLOTS as $slotKey => $slotData)
                                    <tr>
                                        <td
                                            class="sticky left-0 z-10 bg-gray-50 border-r border-gray-200 px-3 py-3 text-center align-top shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">
                                            <div class="font-black text-xl text-primary">{{ $slotKey }}</div>
                                        </td>

                                        @foreach ($classrooms as $room)
                                            @php
                                                $cellCourse = $this->getMatrixStep4()[$slotKey][$room->id] ?? null;
                                            @endphp

                                            <td class="border-r border-gray-100 p-2 align-top h-28 relative transition-opacity duration-300"
                                                :class="!isDraggingTeacher ? 'bg-white' : 'bg-gray-50'">

                                                @if ($cellCourse)
                                                    <!-- CELDA DROPZONE (Porque ya hay un curso) -->
                                                    <div x-data="{ isHovered: false }"
                                                        @dragover.prevent="if(isDraggingTeacher) { isHovered = true }"
                                                        @dragleave.prevent="isHovered = false"
                                                        @drop.prevent="
                                                         isHovered = false; 
                                                         isDraggingTeacher = false;
                                                         let tId = $event.dataTransfer.getData('teacher_id');
                                                         let type = $event.dataTransfer.getData('type');
                                                         if(tId && type === 'teacher') { 
                                                             $wire.assignTeacherToCourse(tId, {{ $cellCourse['id'] }}); 
                                                         }
                                                     "
                                                        wire:click="removeTeacherFromCourse({{ $cellCourse['id'] }})"
                                                        class="h-full border-2 rounded-xl p-3 flex flex-col justify-between transition-all relative overflow-hidden group/coursedrop"
                                                        :class="{
                                                            'border-primary bg-primary/10 shadow-inner scale-100 ring-2 ring-primary/40': isHovered,
                                                            'border-dashed border-primary/60 bg-white ring-4 ring-primary/10 cursor-pointer shadow-md': isDraggingTeacher &&
                                                                !isHovered,
                                                            'border-gray-200 bg-gray-50 hover:border-gray-300 cursor-pointer':
                                                                !isDraggingTeacher && !isHovered
                                                        }">

                                                        <div class="relative z-10 pointer-events-none">
                                                            <div
                                                                class="font-black text-[11px] leading-tight text-gray-800 line-clamp-2">
                                                                {{ $cellCourse['subject_name'] }}
                                                            </div>

                                                            <div class="mt-2 text-[10px] font-bold p-1 rounded transition-colors flex items-center gap-1"
                                                                :class="isHovered ? 'bg-white text-primary' :
                                                                    'bg-primary/10 text-primary'">
                                                                <span
                                                                    class="material-symbols-outlined text-[12px] font-black pointer-events-none">person</span>
                                                                <span
                                                                    class="truncate pointer-events-none">{{ $cellCourse['teacher'] ?? 'Mueve a un maestro aquí' }}</span>
                                                            </div>
                                                        </div>

                                                        <!-- Helper visual remove -->
                                                        @if ($cellCourse['teacher'])
                                                            <div class="absolute top-2 right-2 opacity-0 group-hover/coursedrop:opacity-100 text-gray-400 hover:text-red-500 transition-colors pointer-events-auto"
                                                                title="Quitar maestro">
                                                                <span
                                                                    class="material-symbols-outlined text-[14px]">close</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <!-- Espacio vacío intocable -->
                                                    <div
                                                        class="h-full w-full rounded-xl flex items-center justify-center opacity-30 select-none">
                                                        <span
                                                            class="material-symbols-outlined text-gray-300 text-sm">block</span>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>
    </div>
</div>
