<div>
    <x-slot name="header">
        Maestros
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Barra de Herramientas -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="relative w-full sm:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400 text-xl">search</span>
                </div>
                <input type="text" wire:model.live="search" class="form-input block w-full pl-10 rounded-lg border-gray-200" placeholder="Buscar maestro...">
            </div>
            
            <a href="{{ route('teachers.create') }}" class="flex items-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-medium hover:bg-primary/90 w-full sm:w-auto justify-center">
                <span class="material-symbols-outlined">add</span>
                Nuevo Maestro
            </a>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('message') }}
            </div>
        @endif

        <!-- Listado de Maestros -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-4">ID / Empleado</th>
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Departamento</th>
                            <th class="px-6 py-4">Tipo</th>
                            <th class="px-6 py-4">Prioridad</th>
                            <th class="px-6 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($teachers as $teacher)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $teacher->employee_id ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $teacher->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $teacher->department->code ?? 'Sin Depto' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="capitalize {{ $teacher->type === 'base' ? 'text-blue-600' : 'text-purple-600' }}">
                                        {{ $teacher->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded bg-gray-100 text-xs">
                                        {{ $teacher->priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('teachers.edit', $teacher) }}" class="text-primary hover:text-primary/80">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <button wire:click="deleteTeacher({{ $teacher->id }})" wire:confirm="¿Está seguro de eliminar este maestro?" class="text-red-500 hover:text-red-700">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    No se encontraron maestros.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($teachers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $teachers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
