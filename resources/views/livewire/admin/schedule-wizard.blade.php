<div>
    <x-slot name="header">
        Asistente de Horarios (Wizard) - {{ $period }}
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8 pb-20 mt-8">
        
        <!-- Breadcrumbs / Steps -->
        <div class="flex items-center justify-center space-x-4 mb-8">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 {{ $currentStep >= 1 ? 'bg-primary text-white shadow-lg shadow-primary/30 scale-110 font-black' : 'bg-gray-200 text-gray-500 font-bold border border-gray-300' }} rounded-full flex items-center justify-center transition-all duration-300">
                    1
                </div>
                <span class="{{ $currentStep >= 1 ? 'text-primary font-black' : 'text-gray-400 font-medium' }} text-sm">Importación CSV</span>
            </div>
            <div class="w-16 h-1 rounded-full {{ $currentStep >= 2 ? 'bg-primary' : 'bg-gray-200' }} transition-colors duration-300"></div>

            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 {{ $currentStep >= 2 ? 'bg-primary text-white shadow-lg shadow-primary/30 scale-110 font-black' : 'bg-gray-200 text-gray-500 font-bold border border-gray-300' }} rounded-full flex items-center justify-center transition-all duration-300">
                    2
                </div>
                <span class="{{ $currentStep >= 2 ? 'text-primary font-black' : 'text-gray-400 font-medium' }} text-sm">Carga Académica</span>
            </div>
            <div class="w-16 h-1 rounded-full {{ $currentStep >= 3 ? 'bg-primary' : 'bg-gray-200' }} transition-colors duration-300"></div>

            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 {{ $currentStep >= 3 ? 'bg-primary text-white shadow-lg shadow-primary/30 scale-110 font-black' : 'bg-gray-200 text-gray-500 font-bold border border-gray-300' }} rounded-full flex items-center justify-center transition-all duration-300">
                    3
                </div>
                <span class="{{ $currentStep >= 3 ? 'text-primary font-black' : 'text-gray-400 font-medium' }} text-sm">Horarios y Salones</span>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3 animate-in fade-in duration-500">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- PASO 1 -->
        @if($currentStep === 1)
        <div class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-500">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-primary/10 text-primary p-3 rounded-xl">
                            <span class="material-symbols-outlined text-3xl">upload_file</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 leading-tight">Paso 1: Detectar Grupos a Programar</h2>
                            <p class="text-gray-500 font-medium">Sube el listado en formato CSV base que contenga la planeación requerida.</p>
                        </div>
                    </div>
                </div>

                @error('file')
                    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl flex items-center gap-3 mt-4">
                        <span class="material-symbols-outlined">error</span>
                        <p class="font-bold text-sm">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <div class="p-8">
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-bold text-gray-700">Seleccionar Archivo .CSV</label>
                    <input type="file" wire:model="file" accept=".csv"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:transition-all cursor-pointer bg-gray-50 rounded-xl border border-gray-200 focus:border-primary focus:ring-0">

                    <div wire:loading wire:target="file" class="text-primary mt-3 text-sm font-bold animate-pulse flex items-center gap-2">
                        <span class="material-symbols-outlined animate-spin text-sm">refresh</span>
                        Procesando y creando borradores...
                    </div>
                </div>

                @if(!empty($importedCourses))
                <div class="mt-8 border rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest border-b">
                            <tr>
                                <th class="px-6 py-3">Materia / Clave</th>
                                <th class="px-6 py-3 text-center">Alumnos</th>
                                <th class="px-6 py-3 text-center">Configuraciones Base</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($importedCourses as $row)
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-6 py-3">
                                        <div class="font-bold text-gray-700">{{ $row['subject_name'] }}</div>
                                        <div class="text-[10px] font-mono text-primary font-bold">{{ $row['subject_code'] }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-center font-bold text-gray-600">
                                        {{ $row['students'] }}
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="font-mono bg-blue-50 text-blue-600 px-3 py-1 rounded font-black text-xs">
                                            {{ $row['slots'] ?: 'SIN DEFINIR' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex justify-end">
                    <button wire:click="nextStep" class="flex items-center gap-2 px-8 py-3 bg-primary text-white rounded-xl font-black shadow-lg shadow-primary/30 hover:scale-105 active:scale-95 transition-all">
                        <span>Paso 2: Asignar Maestros</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
        @endif


        <!-- PASO 2 -->
        @if($currentStep === 2)
        <div class="animate-in fade-in duration-500 grid md:grid-cols-4 gap-8">
            <div class="md:col-span-3 bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-black text-gray-900 leading-tight">Paso 2: Asignación de Maestros</h2>
                        <p class="text-gray-500 font-medium text-sm">Selecciona quién impartirá cada grupo.</p>
                    </div>
                </div>
                
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest rounded-xl">
                            <tr>
                                <th class="px-6 py-3 rounded-l-xl">Clave / Materia / Grupo</th>
                                <th class="px-6 py-3 text-center">Horas requeridas</th>
                                <th class="px-6 py-3 rounded-r-xl">Docente Asignado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($coursesStep2 as $course)
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $course->subject->name }} ({{ $course->group_code }})</div>
                                        <div class="flex gap-2">
                                            <span class="text-[10px] font-mono text-primary font-bold">{{ $course->subject->code }}</span>
                                            <span class="text-[10px] bg-gray-100 rounded px-1 text-gray-500 font-bold">{{ $course->students_count }} Alum.</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-black text-gray-600">
                                        {{ $course->subject->weekly_hours }} Hrs
                                    </td>
                                    <td class="px-6 py-4">
                                        <select wire:model.live="selectedTeachers.{{ $course->id }}" 
                                            class="block w-full text-sm rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-gray-50 font-bold text-gray-700">
                                            <option value="">-- Sin asignar --</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-100 flex justify-between bg-gray-50/50">
                    <button wire:click="previousStep" class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-colors">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Regresar
                    </button>
                    <button wire:click="nextStep" class="flex items-center gap-2 px-8 py-3 bg-primary text-white rounded-xl font-black shadow-lg shadow-primary/30 hover:scale-105 active:scale-95 transition-all">
                        <span>Paso 3: Horarios y Salones</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>
            </div>

            <!-- Panel lateral Cargas Docentes -->
            <div class="md:col-span-1 bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden self-start sticky top-8">
                <div class="p-4 border-b border-gray-100 bg-gray-900 text-white">
                    <h3 class="font-black flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-white">monitoring</span>
                        Carga Sumarizada
                    </h3>
                </div>
                <div class="p-4 space-y-3 max-h-[600px] overflow-y-auto">
                    @foreach($this->teacherWorkloads as $w)
                        @php
                            $percentage = $w['max'] > 0 ? min(100, round(($w['assigned'] / $w['max']) * 100)) : 0;
                            // Colors: Green if above min, Orange/Red if below min, Red if past max
                            $colorClass = 'bg-amber-100 text-amber-800'; 
                            $barClass = 'bg-amber-400';
                            if ($w['assigned'] >= $w['min']) {
                                $colorClass = 'bg-green-100 text-green-800';
                                $barClass = 'bg-green-500';
                            }
                            if ($w['assigned'] > $w['max']) {
                                $colorClass = 'bg-red-100 text-red-800 animate-pulse';
                                $barClass = 'bg-red-500';
                            }
                            if ($w['assigned'] == 0) {
                                $colorClass = 'bg-gray-100 text-gray-500';
                                $barClass = 'bg-gray-300';
                            }
                        @endphp
                        <div class="border border-gray-100 rounded-xl p-3 shadow-sm hover:shadow-md transition-all">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-xs truncate max-w-[65%] text-gray-700" title="{{ $w['name'] }}">{{ $w['name'] }}</span>
                                <span class="{{ $colorClass }} font-black text-[10px] px-2 py-0.5 rounded-full whitespace-nowrap">
                                    {{ $w['assigned'] }} / {{ $w['min'] }} hrs
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ $barClass }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                            @if($w['assigned'] > $w['max'])
                                <div class="text-[9px] text-red-500 font-bold tracking-tight mt-1 text-right">EXCEDE LIMITE ({{ $w['max'] }})</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif


        <!-- PASO 3 -->
        @if($currentStep === 3)
        <div class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden animate-in fade-in duration-500">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black text-gray-900 leading-tight">Paso 3: Bloques de Tiempo y Salón</h2>
                    <p class="text-gray-500 font-medium text-sm">Resuelve conflictos de cupo y selecciona el espacio final en horario.</p>
                </div>
            </div>
                
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest rounded-xl">
                        <tr>
                            <th class="px-4 py-3 rounded-l-xl">Materia y Docente</th>
                            <th class="px-4 py-3 text-center">Filtro JSON Slot</th>
                            <th class="px-4 py-3">Decisión Horario (Final Slot)</th>
                            <th class="px-4 py-3 rounded-r-xl">Selección de Salón Físico</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($coursesStep3 as $course)
                            @php
                                $slotsArr = $course->possible_slots ? json_decode($course->possible_slots, true) : [];
                            @endphp
                            <tr class="hover:bg-primary/5 transition-colors">
                                <td class="px-4 py-4 max-w-xs">
                                    <div class="font-black text-gray-800 text-sm truncate" title="{{ $course->subject->name }}">{{ $course->subject->name }}</div>
                                    <div class="text-xs font-medium text-gray-500 flex gap-2 items-center mt-1">
                                        <span class="material-symbols-outlined text-[14px]">person</span>
                                        <span class="truncate">{{ $course->teacher->name ?? '¿?' }}</span>
                                    </div>
                                    <div class="mt-2 text-[10px] font-bold bg-blue-50 text-blue-700 px-2 py-0.5 rounded inline-block">
                                        Requerimiento: {{ $course->students_count }} pupitres
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    @if(empty($slotsArr))
                                        <span class="text-xs text-gray-400 font-medium">Libre</span>
                                    @else
                                        <div class="flex gap-1 justify-center flex-wrap">
                                            @foreach($slotsArr as $slot)
                                                <span class="font-mono bg-indigo-50 border border-indigo-100 text-indigo-700 px-2 py-0.5 rounded font-black text-xs">
                                                    {{ $slot }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-4 min-w-[150px]">
                                    <select wire:model="selectedFinalSlots.{{ $course->id }}" class="block w-full text-sm rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-gray-50 font-bold text-gray-700">
                                        <option value="">Selecciona horario</option>
                                        @foreach (\App\Helpers\TimeSlotHelper::SLOTS as $s => $times)
                                            <!-- Optionally pre-filter, but requirements dictate the user choices -->
                                            <option value="{{ $s }}">Bloque {{ $s }} ({{ $times['start'] }} - {{ $times['end'] }})</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-4 py-4 min-w-[200px]">
                                    <div class="relative">
                                        <select wire:model.live="selectedClassrooms.{{ $course->id }}" class="block w-full text-sm rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 bg-white font-bold text-gray-700 pr-10">
                                            <option value="">Selecciona salón</option>
                                            @foreach($classrooms as $room)
                                                <option value="{{ $room->id }}">
                                                    {{ $room->name }} (Cap: {{ $room->capacity }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <!-- Realtime Validation Tooltip/Icon -->
                                        @if(isset($selectedClassrooms[$course->id]) && $selectedClassrooms[$course->id] != '')
                                            @php
                                                $selectedRoom = $classrooms->firstWhere('id', $selectedClassrooms[$course->id]);
                                            @endphp
                                            @if($selectedRoom && $selectedRoom->capacity < $course->students_count)
                                                <div class="absolute right-2 top-2 text-red-500 animate-pulse" title="¡Este salón no tiene capacidad suficiente!">
                                                    <span class="material-symbols-outlined">warning</span>
                                                </div>
                                                <div class="text-[10px] text-red-500 font-bold mt-1 tracking-tight">Capacidad insuficiente (Faltan {{ $course->students_count - $selectedRoom->capacity }})</div>
                                            @else
                                                <div class="absolute right-2 top-2 text-green-500">
                                                    <span class="material-symbols-outlined">check_circle</span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-gray-100 flex justify-between bg-gray-50/50">
                <button wire:click="previousStep" class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition-colors">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    Regresar
                </button>
                <button wire:click="publishSchedules" wire:loading.attr="disabled" class="flex items-center gap-2 px-8 py-3 bg-gray-900 text-white rounded-xl font-black shadow-xl hover:scale-105 active:scale-95 transition-all">
                    <span wire:loading.remove wire:target="publishSchedules" class="material-symbols-outlined text-sm">publish</span>
                    <span wire:loading wire:target="publishSchedules" class="material-symbols-outlined text-sm animate-spin">refresh</span>
                    <span>PUBLICAR HORARIOS Y TERMINAR</span>
                </button>
            </div>
        </div>
        @endif

    </div>
</div>
