<div>
    <x-slot name="header">
        {{ $teacher && $teacher->exists ? 'Editar Maestro' : 'Alta de Maestro' }}
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <form wire:submit="save" class="p-6 flex flex-col gap-6">
                <!-- Información Básica -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Nombre Completo</label>
                        <input type="text" wire:model ="name" class="form-input rounded-lg border-gray-200" placeholder="Ej: Dr. Juan Pérez">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Número de Empleado / ID</label>
                        <input type="text" wire:model ="employee_id" class="form-input rounded-lg border-gray-200" placeholder="Ej: EMP-123">
                        @error('employee_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Tipo y Departamento -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Departamento</label>
                        <select wire:model ="department_id" class="form-select rounded-lg border-gray-200">
                            <option value="">Seleccione un departamento</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                            @endforeach
                        </select>
                        @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Tipo de Contrato</label>
                        <div class="flex gap-4 mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model ="type" value="base" class="form-radio text-primary">
                                <span class="ml-2 text-sm text-gray-700">Base</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model ="type" value="honorarios" class="form-radio text-primary">
                                <span class="ml-2 text-sm text-gray-700">Honorarios</span>
                            </label>
                        </div>
                        @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Reglas de Horario -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Prioridad Administrativa</label>
                        <input type="number" wire:model ="priority" class="form-input rounded-lg border-gray-200" placeholder="0 = Normal, 10 = Alta">
                        <p class="text-xs text-gray-500">Valores altos resuelven conflictos a su favor.</p>
                        @error('priority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Mínimo de Horas</label>
                        <input type="number" wire:model ="min_hours" class="form-input rounded-lg border-gray-200" placeholder="Ej: 0">
                        @error('min_hours') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Límite Máximo de Horas</label>
                        <input type="number" wire:model ="max_hours" class="form-input rounded-lg border-gray-200" placeholder="Ej: 40">
                        @error('max_hours') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('teachers.index') }}" class="flex items-center justify-center rounded-lg h-10 px-4 bg-gray-100 text-gray-800 hover:bg-gray-200 text-sm font-medium">
                        Cancelar
                    </a>
                    <button type="submit" class="flex items-center justify-center gap-2 rounded-lg h-10 px-6 bg-primary text-white text-sm font-medium hover:bg-primary/90">
                        <span wire:loading.remove>Guardar Maestro</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
