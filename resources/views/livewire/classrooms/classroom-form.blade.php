<div>
    <x-slot name="header">
        {{ $classroom && $classroom->exists ? 'Editar Salón' : 'Nuevo Salón' }}
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <form wire:submit="save" class="p-6 flex flex-col gap-6">
                <!-- Información del Salón -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Nombre o Código del Salón</label>
                        <input type="text" wire:model ="name" class="form-input rounded-lg border-gray-200" placeholder="Ej: Laboratorio 1, Aula A2">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Edificio</label>
                        <select wire:model ="building_id" class="form-select rounded-lg border-gray-200">
                            <option value="">Seleccione edificio...</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}">{{ $building->name }} ({{ $building->code }})</option>
                            @endforeach
                        </select>
                        @error('building_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-gray-700">Capacidad (Alumnos)</label>
                        <input type="number" wire:model ="capacity" class="form-input rounded-lg border-gray-200" placeholder="Ej: 30">
                        @error('capacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('classrooms.index') }}" class="flex items-center justify-center rounded-lg h-10 px-4 bg-gray-100 text-gray-800 hover:bg-gray-200 text-sm font-medium">
                        Cancelar
                    </a>
                    <button type="submit" class="flex items-center justify-center gap-2 rounded-lg h-10 px-6 bg-primary text-white text-sm font-medium hover:bg-primary/90">
                        <span wire:loading.remove>Guardar Salón</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
