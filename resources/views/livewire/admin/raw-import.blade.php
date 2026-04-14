<div>
    <x-slot name="header">
        Importación Masiva de Requerimientos
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Input Section -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-primary/10 text-primary p-3 rounded-xl">
                        <span class="material-symbols-outlined text-3xl">upload_file</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 leading-tight">Pegar Datos de Excel</h2>
                        <p class="text-gray-500">Copia y pega las columnas de tu reporte de carga académica.</p>
                    </div>
                </div>
                
                @if($message)
                    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3 animate-pulse">
                        <span class="material-symbols-outlined">check_circle</span>
                        <p class="font-bold text-sm">{{ $message }}</p>
                    </div>
                @endif
            </div>

            <div class="p-8">
                <div class="mb-6">
                    <textarea 
                        wire:model="rawData" 
                        rows="8" 
                        class="w-full rounded-2xl border-gray-200 focus:border-primary focus:ring focus:ring-primary/20 transition-all font-mono text-xs p-4 bg-gray-50"
                        placeholder="Semestre Materia Clave Alumnos Ajuste Grupos Slots..."
                    ></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-xs font-black text-gray-400 uppercase tracking-widest">
                        Periodo Destino: <span class="text-primary">{{ $period }}</span>
                    </div>
                    
                    <button 
                        wire:click="import" 
                        wire:loading.attr="disabled"
                        class="flex items-center gap-3 px-8 py-4 bg-primary text-white rounded-2xl font-black shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50"
                    >
                        <span wire:loading.remove class="material-symbols-outlined">bolt</span>
                        <span wire:loading class="material-symbols-outlined animate-spin">refresh</span>
                        <span>PROCESAR Y VERIFICAR</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Preview Table Section -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
                <h3 class="text-lg font-black text-gray-900">Últimos Registros Importados</h3>
                <span class="text-xs font-bold text-gray-400">Mostrando últimos 10</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Semestre</th>
                            <th class="px-8 py-4">Materia</th>
                            <th class="px-8 py-4">Clave</th>
                            <th class="px-8 py-4 text-center">Grupos</th>
                            <th class="px-8 py-4">Slots Detecados</th>
                            <th class="px-8 py-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentCourses as $course)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-8 py-4 font-black text-gray-900">{{ $course->subject->semester }}°</td>
                                <td class="px-8 py-4 font-bold text-gray-700">{{ $course->subject->name }}</td>
                                <td class="px-8 py-4 font-mono text-primary font-bold">{{ $course->subject->code }}</td>
                                <td class="px-8 py-4 text-center">
                                    <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-lg font-black">{{ $course->groups_count }}</span>
                                </td>
                                <td class="px-8 py-4 font-mono text-xs text-gray-500">{{ $course->requirement_slot }}</td>
                                <td class="px-8 py-4">
                                    <span class="flex items-center gap-1 text-green-600 font-bold text-xs uppercase">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                        Importado
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center text-gray-400 italic font-medium">
                                    No hay registros recientes. Pega datos arriba para comenzar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
