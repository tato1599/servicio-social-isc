<div>
    <x-slot name="header">
        Matriz de Horarios por Salón
    </x-slot>

    <div class="space-y-6">
        <!-- Controls -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="bg-primary/10 text-primary p-3 rounded-xl">
                    <span class="material-symbols-outlined text-2xl">grid_view</span>
                </div>
                <div>
                    <h2 class="text-xl font-black text-gray-900 leading-tight">Mapa de Ocupación Estructural</h2>
                    <p class="text-xs text-gray-500 font-medium">Visualización tipo Excel de todos los salones simultáneamente.</p>
                </div>
            </div>

            <div class="flex bg-gray-100 p-1 rounded-xl">
                @foreach(['Lunes' => 1, 'Martes' => 2, 'Miércoles' => 3, 'Jueves' => 4, 'Viernes' => 5] as $label => $val)
                    <button 
                        wire:click="setDay({{ $val }})"
                        class="px-4 py-2 rounded-lg text-xs font-black uppercase transition-all {{ $dayOfWeek == $val ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Matrix Container -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden">
            <div class="overflow-auto max-h-[75vh] custom-scrollbar">
                <table class="w-full border-separate border-spacing-0">
                    <thead class="sticky top-0 z-20">
                        <tr class="bg-gray-900 text-white">
                            <th class="sticky left-0 z-30 bg-gray-900 p-4 border-b border-r border-gray-800 text-[10px] font-black uppercase tracking-widest min-w-[120px]">
                                HORARIO / SALA
                            </th>
                            @foreach($classrooms as $room)
                                <th class="p-4 border-b border-r border-gray-800 text-sm font-black min-w-[160px] text-center bg-gray-900">
                                    {{ $room->name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slots as $letter => $time)
                            <tr>
                                <td class="sticky left-0 z-10 bg-gray-50 p-4 border-b border-r border-gray-200 text-center">
                                    <div class="text-sm font-black text-primary">{{ $letter }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold whitespace-nowrap">{{ $time }}</div>
                                </td>
                                @foreach($classrooms as $room)
                                    <td class="p-1 border-b border-r border-gray-100 min-h-[100px] align-top">
                                        @if(isset($matrix[$letter][$room->id]))
                                            @php $cell = $matrix[$letter][$room->id]; @endphp
                                            <div class="h-full rounded-xl border p-3 {{ $cell['color'] }} shadow-sm hover:shadow-md transition-all group relative cursor-help">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="text-[10px] font-black uppercase bg-white/50 px-1.5 py-0.5 rounded">{{ $cell['subject_code'] }}</span>
                                                    <span class="text-[10px] font-bold opacity-60">{{ $cell['semester'] }}°</span>
                                                </div>
                                                <p class="text-[11px] font-black leading-tight mb-2 line-clamp-2">{{ $cell['subject_name'] }}</p>
                                                <div class="flex items-center gap-1.5 opacity-80">
                                                    <span class="material-symbols-outlined text-[14px]">person</span>
                                                    <span class="text-[10px] font-bold truncate">{{ $cell['teacher_name'] }}</span>
                                                </div>

                                                <!-- Tooltip -->
                                                <div class="absolute inset-0 bg-black/90 text-white p-4 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none flex flex-col justify-center">
                                                    <p class="text-xs font-black text-primary mb-1">{{ $cell['subject_code'] }}</p>
                                                    <p class="text-sm font-bold leading-tight mb-2">{{ $cell['subject_name'] }}</p>
                                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black">Profesor:</p>
                                                    <p class="text-xs font-bold">{{ $cell['teacher_name'] }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-full min-h-[80px] flex items-center justify-center opacity-5 group">
                                                <span class="material-symbols-outlined text-gray-300 group-hover:scale-110 transition-transform">add_circle</span>
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

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</div>
