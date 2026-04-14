<div>
    <x-slot name="header">
        Gestión de Aulas / Salones
    </x-slot>

    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="relative w-full sm:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400 text-xl">search</span>
                </div>
                <input type="text" wire:model.live="search" class="form-input block w-full pl-10 rounded-lg border-gray-200" placeholder="Buscar salón...">
            </div>
            
            <a href="{{ route('classrooms.create') }}" class="flex items-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-medium hover:bg-primary/90 w-full sm:w-auto justify-center">
                <span class="material-symbols-outlined">add</span>
                Nuevo Salón
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
                            <th class="px-6 py-4">Nombre / Código</th>
                            <th class="px-6 py-4">Edificio</th>
                            <th class="px-6 py-4">Capacidad</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($classrooms as $classroom)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $classroom->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $classroom->building->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">{{ $classroom->capacity }} alumnos</span>
                                </td>
                                <td class="px-6 py-4 flex justify-end gap-2">
                                    <a href="{{ route('classrooms.edit', $classroom) }}" class="text-primary hover:text-primary/80">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <button wire:click="deleteClassroom({{ $classroom->id }})" wire:confirm="¿Está seguro de eliminar este salón?" class="text-red-500 hover:text-red-700">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    No se encontraron salones.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($classrooms->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $classrooms->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
