<div>
    <x-slot name="header">
        {{ $subject && $subject->exists ? 'Editar Materia' : 'Alta de Materia' }}
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-[#1A2836] rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <form wire:submit="save" class="p-6 flex flex-col gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Clave / Código</label>
                        <input type="text" wire:model ="code" class="form-input rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Ej: 2EA">
                        @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Nombre de la Materia</label>
                        <input type="text" wire:model ="name" class="form-input rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Ej: Cálculo Diferencial">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Semestre</label>
                        <input type="number" wire:model ="semester" class="form-input rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="1-12">
                        @error('semester') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Horas Semanales</label>
                        <input type="number" wire:model ="weekly_hours" class="form-input rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Ej: 4">
                        @error('weekly_hours') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Departamento</label>
                        <select wire:model ="department_id" class="form-select rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Seleccione...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('subjects.index') }}" class="flex items-center justify-center rounded-lg h-10 px-4 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 text-sm font-medium">
                        Cancelar
                    </a>
                    <button type="submit" class="flex items-center justify-center gap-2 rounded-lg h-10 px-6 bg-primary text-white text-sm font-medium hover:bg-primary/90">
                        Guardar Materia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
