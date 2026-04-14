<div>
    <x-slot name="header">
        Asignación de Horarios (Cursos)
    </x-slot>

    <div class="max-w-6xl">
        <div class="bg-white dark:bg-[#1A2836] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <form wire:submit="save" class="p-6 flex flex-col gap-8">
                
                <!-- Datos del Curso -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Materia</label>
                        <select wire:model="subject_id" class="form-select rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm">
                            <option value="">Seleccione materia...</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->code }} - {{ $subj->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Maestro</label>
                        <select wire:model="teacher_id" class="form-select rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm">
                            <option value="">Seleccione maestro...</option>
                            @foreach($teachers as $teach)
                                <option value="{{ $teach->id }}">{{ $teach->name }} ({{ $teach->type }})</option>
                            @endforeach
                        </select>
                        @error('teacher_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Grupo</label>
                        <input type="text" wire:model="group_code" class="form-input rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm" placeholder="Ej: A">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Periodo</label>
                        <input type="text" wire:model="period" class="form-input rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm">
                    </div>
                </div>

                <!-- Detalle de Horario -->
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg">
                        <h3 class="font-bold text-gray-900 dark:text-white">Distribución de Horas</h3>
                        <button type="button" wire:click="addSchedule" class="flex items-center gap-2 rounded-lg h-9 px-4 bg-primary text-white text-xs font-medium hover:bg-primary/90">
                            <span class="material-symbols-outlined text-sm">add</span>
                            Añadir Horario/Salón
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($schedules_data as $index => $slot)
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end p-4 border border-gray-100 dark:border-gray-700 rounded-lg bg-white dark:bg-transparent">
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-gray-500 uppercase">Día</label>
                                    <select wire:model="schedules_data.{{ $index }}.day_of_week" class="form-select text-xs rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miércoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                        <option value="6">Sábado</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-gray-500 uppercase">Inicio</label>
                                    <input type="time" wire:model ="schedules_data.{{ $index }}.start_time" class="form-input text-xs rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-gray-500 uppercase">Fin</label>
                                    <input type="time" wire:model ="schedules_data.{{ $index }}.end_time" class="form-input text-xs rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-gray-500 uppercase">Salón</label>
                                    <select wire:model ="schedules_data.{{ $index }}.classroom_id" class="form-select text-xs rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                        <option value="">Seleccione salón...</option>
                                        @foreach($classrooms as $room)
                                            <option value="{{ $room->id }}">{{ $room->name }} (Cap. {{ $room->capacity }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" wire:click="removeSchedule({{ $index }})" class="text-red-500 hover:text-red-700 mb-2">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Acciones Finales -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('courses.index') }}" class="flex items-center justify-center rounded-lg h-10 px-4 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 text-sm font-medium">
                        Cancelar
                    </a>
                    <button type="submit" class="flex items-center justify-center gap-2 rounded-lg h-10 px-8 bg-primary text-white text-sm font-medium hover:bg-primary/90">
                        Guardar Horario Completo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
